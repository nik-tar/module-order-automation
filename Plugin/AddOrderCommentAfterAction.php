<?php

namespace Niktar\OrderAutomation\Plugin;

use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Sales\Api\Data\OrderStatusHistoryInterfaceFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Niktar\OrderAutomation\Action\ActionInterface;
use Niktar\OrderAutomation\Registry\AfterAction;

class AddOrderCommentAfterAction
{
    /**
     * @param AfterAction $afterActionRegistry
     * @param OrderStatusHistoryInterfaceFactory $statusHistoryFactory
     * @param OrderRepositoryInterface $orderRepository
     * @param TimezoneInterface $timezone
     */
    public function __construct(
        private readonly AfterAction $afterActionRegistry,
        private readonly OrderStatusHistoryInterfaceFactory $statusHistoryFactory,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly TimezoneInterface $timezone
    ) {
    }

    public function afterExecute(ActionInterface $subject, $result)
    {
        $actionLogs = $this->afterActionRegistry->getActionLogs();
        foreach ($actionLogs as $actionLog) {
            /** @see \Magento\Sales\Model\Service\OrderService::addComment */
            $order = $this->orderRepository->get($actionLog->getOrderId());
            $statusHistory = $this->statusHistoryFactory->create();
            $statusHistory->setStatus($order->getStatus())
                ->setIsCustomerNotified(null)
                ->setIsVisibleOnFront(0)
                ->setParentId($actionLog->getOrderId())
                ->setComment(__(
                    'Order Automation Rule #%1 was processed on this order with "%2" status.'
                    . (empty($actionLog->getMessage()) ? '' : "\n\nMessage: %3."),
                    $actionLog->getRuleId(),
                    $actionLog->getStatus(),
                    $actionLog->getMessage()
                )->render());
            $order->addStatusHistory($statusHistory);
            $this->orderRepository->save($order);
        }
        $this->afterActionRegistry->reset();
        return null;
    }
}
