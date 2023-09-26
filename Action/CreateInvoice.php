<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Action;

use Exception;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\DB\Transaction as TransactionSave;
use Magento\Framework\DB\TransactionFactory as TransactionSaveFactory;
use Magento\Sales\Api\Data\InvoiceInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Email\Sender\InvoiceSender;
use Magento\Sales\Model\Service\InvoiceService;
use Niktar\OrderAutomation\Api\Data\RuleInterface;
use Psr\Log\LoggerInterface;

class CreateInvoice
{
    /**
     * @param InvoiceService $invoiceService
     * @param TransactionSaveFactory $transactionSaveFactory
     * @param InvoiceSender $invoiceSender
     * @param LoggerInterface $logger
     * @param State $state
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        private readonly InvoiceService $invoiceService,
        private readonly TransactionSaveFactory $transactionSaveFactory,
        private readonly InvoiceSender $invoiceSender,
        private readonly LoggerInterface $logger,
        private readonly State $state,
        private readonly OrderRepositoryInterface $orderRepository
    ) {
    }

    /**
     * @param int $orderId
     * @param RuleInterface $rule
     * @return InvoiceInterface|null
     */
    public function execute(int $orderId, RuleInterface $rule): ?InvoiceInterface
    {
        /** @var Order $order */
        $order = $this->orderRepository->get($orderId);
        if (!$order->canInvoice()) {
            return null;
        }

        try {
            $invoice = $this->invoiceService->prepareInvoice($order);

            $invoiceComment = __(
                "Invoice created automatically by Order Automation Rule.\n\n" .
                "Order Automation Rule ID: %1",
                $rule->getRuleId()
            );

            $invoice->addComment(
                $invoiceComment,
                false,
                true
            );

            $invoice->setCustomerNoteNotify(false);

            $invoice->register();
        } catch (Exception $exception) {
            $this->logger->error(
                __(
                    'Cannot prepare an invoice while processing order automation rule "%1" and order "%2". Message: %3',
                    $rule->getRuleId(),
                    $order->getIncrementId(),
                    $exception->getMessage()
                ),
                [
                    'exception' => $exception
                ]
            );

            return null;
        }

        $order->setCustomerNoteNotify(true);
        $order->setIsInProcess(true);

        /** @var TransactionSave $transactionSave */
        $transactionSave = $this->transactionSaveFactory->create();

        $transactionSave
            ->addObject($invoice)
            ->addObject($order);

        try {
            $transactionSave->save();
        } catch (Exception $exception) {
            $this->logger->error(
                __(
                    'The order automation rule #%1 cannot create an invoice for order "%2". Message: %3',
                    $rule->getRuleId(),
                    $order->getIncrementId(),
                    $exception->getMessage()
                ),
                [
                    'exception' => $exception
                ]
            );

            return null;
        }

        try {
            $this->state->emulateAreaCode(
                Area::AREA_FRONTEND,
                [$this->invoiceSender, 'send'],
                [$invoice]
            );
        } catch (Exception $exception) {
            $this->logger->error(
                __(
                    'Cannot send an invoice email to customer while processing order automation rule "%1" and order "%2". Message: %3',
                    $rule->getRuleId(),
                    $order->getIncrementId(),
                    $exception->getMessage()
                ),
                [
                    'exception' => $exception
                ]
            );
        }

        return $invoice;
    }
}
