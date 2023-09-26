<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Action\Custom;

use Magento\Framework\Exception\InvalidArgumentException;
use Magento\Sales\Api\OrderManagementInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order\Config as SalesOrderConfig;
use Niktar\OrderAutomation\Action\Action;
use Niktar\OrderAutomation\Action\CreateInvoice;
use Niktar\OrderAutomation\Api\ActionLogRepositoryInterface;
use Niktar\OrderAutomation\Api\Data\ActionLogInterfaceFactory;
use Niktar\OrderAutomation\Api\Data\RuleInterface;
use Niktar\OrderAutomation\Helper\Config;
use Niktar\OrderAutomation\Helper\Rule as RuleHelper;
use Niktar\OrderAutomation\Registry\AfterAction;
use Niktar\OrderAutomation\Ui\Source\OrderStatus;
use Psr\Log\LoggerInterface;

class ChangeOrderStatus extends Action
{
    protected const ERROR_MESSAGE_BASE = 'There was an error while updating order status by order automation rule. ';
    /**
     * @param Config $config
     * @param RuleHelper $ruleHelper
     * @param ActionLogInterfaceFactory $actionLogFactory
     * @param ActionLogRepositoryInterface $actionLogRepository
     * @param AfterAction $afterActionRegistry
     * @param LoggerInterface $logger
     * @param OrderManagementInterface $orderManagement
     * @param SalesOrderConfig $orderConfig
     * @param CreateInvoice $createInvoice
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        Config $config,
        RuleHelper $ruleHelper,
        ActionLogInterfaceFactory $actionLogFactory,
        ActionLogRepositoryInterface $actionLogRepository,
        AfterAction $afterActionRegistry,
        LoggerInterface $logger,
        private readonly OrderManagementInterface $orderManagement,
        private readonly SalesOrderConfig $orderConfig,
        private readonly CreateInvoice $createInvoice,
        private readonly OrderRepositoryInterface $orderRepository
    ) {
        parent::__construct($config, $ruleHelper, $actionLogFactory, $actionLogRepository, $afterActionRegistry, $logger);
    }

    /**
     * @inheritDoc
     * @throws InvalidArgumentException
     */
    protected function executeAction(int $orderId, RuleInterface $rule): void
    {
        $newStatus = $rule->getActionData()->getNewOrderStatus();
        if (empty($newStatus)) {
            throw new InvalidArgumentException(
                __('New status is not set in rule. Skipping order. Rule ID: %1', $rule->getRuleId())
            );
        }
        foreach (OrderStatus::STATE_STATUSES as $state) {
            $statuses = $this->orderConfig->getStateStatuses($state, false);
            if (!in_array($newStatus, $statuses, true)) {
                continue;
            }
            $order = $this->orderRepository->get($orderId);
            switch ($state) {
                case \Magento\Sales\Model\Order::STATE_CANCELED:
                    if ($order->canUnhold()) {
                        $this->orderManagement->unHold($orderId);
                    }
                    $this->orderManagement->cancel($orderId);
                    break(2);
                case \Magento\Sales\Model\Order::STATE_HOLDED:
                    $this->orderManagement->hold($orderId);
                    break(2);
                case \Magento\Sales\Model\Order::STATE_COMPLETE:
                    if ($order->canUnhold()) {
                        $this->orderManagement->unHold($orderId);
                    }
                    $this->createInvoice->execute($orderId, $rule);
                    break(2);
                default:
                    break(2);
            }
        }
    }
}
