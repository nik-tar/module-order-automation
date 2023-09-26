<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Action;

use Magento\Framework\Phrase;
use Magento\Sales\Api\Data\OrderStatusHistoryInterfaceFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Email\Sender\OrderCommentSender;

class GenerateComment
{
    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param OrderStatusHistoryInterfaceFactory $statusHistoryFactory
     * @param OrderCommentSender $orderCommentSender
     */
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly OrderStatusHistoryInterfaceFactory $statusHistoryFactory,
        private readonly OrderCommentSender $orderCommentSender
    ) {
    }

    /**
     * @param Phrase $comment
     * @param int $orderId
     * @param bool|null $notify
     * @param bool $visibleOnFront
     * @return void
     * @see \Magento\Sales\Model\Service\OrderService::addComment
     */
    public function execute(
        Phrase $comment,
        int $orderId,
        ?bool $notify = null,
        bool $visibleOnFront = false
    ): void {
        /** @var Order $order */
        $order = $this->orderRepository->get($orderId);
        $statusHistory = $this->statusHistoryFactory->create();
        $statusHistory->setStatus($order->getStatus())
            ->setIsCustomerNotified($notify)
            ->setIsVisibleOnFront($visibleOnFront)
            ->setParentId($orderId)
            ->setComment($comment->render());
        $order->addStatusHistory($statusHistory);
        $this->orderRepository->save($order);

        $notify = $notify ?? false;
        $comment = $statusHistory->getComment() !== null ? trim(strip_tags($statusHistory->getComment())) : '';
        $this->orderCommentSender->send($order, $notify, $comment);
    }
}
