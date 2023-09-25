<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Action;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InvalidArgumentException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\OrderManagementInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order\Config as SalesOrderConfig;
use Niktar\OrderAutomation\Api\ActionLogRepositoryInterface;
use Niktar\OrderAutomation\Api\Data\ActionLogInterface;
use Niktar\OrderAutomation\Api\Data\ActionLogInterfaceFactory;
use Niktar\OrderAutomation\Api\Data\RuleInterface;
use Niktar\OrderAutomation\Helper\Config;
use Niktar\OrderAutomation\Helper\Rule as RuleHelper;
use Niktar\OrderAutomation\Registry\AfterAction;
use Niktar\OrderAutomation\Ui\Source\ActionStatus;
use Niktar\OrderAutomation\Ui\Source\OrderStatus;
use Psr\Log\LoggerInterface;

class ChangeOrderStatus implements ActionInterface
{
    /**
     * @param Config $config
     * @param RuleHelper $ruleHelper
     * @param OrderManagementInterface $orderManagement
     * @param ActionLogInterfaceFactory $actionLogFactory
     * @param ActionLogRepositoryInterface $actionLogRepository
     * @param SalesOrderConfig $orderConfig
     * @param CreateInvoice $createInvoice
     * @param AfterAction $afterActionRegistry
     * @param OrderRepositoryInterface $orderRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly Config $config,
        private readonly RuleHelper $ruleHelper,
        private readonly OrderManagementInterface $orderManagement,
        private readonly ActionLogInterfaceFactory $actionLogFactory,
        private readonly ActionLogRepositoryInterface $actionLogRepository,
        private readonly SalesOrderConfig $orderConfig,
        private readonly CreateInvoice $createInvoice,
        private readonly AfterAction $afterActionRegistry,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @inheritDoc
     */
    public function execute(RuleInterface $rule): void
    {
        if (!$this->config->isEnabled()) {
            return;
        }
        $limit = $this->config->getOrderLimitPerRun();
        $orderIds = $this->ruleHelper->getOrderIdsForRule($rule, $limit);
        $orderIds = array_map(fn ($value) => (int)$value, $orderIds);
        foreach ($orderIds as $orderId) {
            /** @var ActionLogInterface $actionLog */
            $actionLog = $this->actionLogFactory->create();
            try {
                $this->updateOrderStatus($orderId, $rule);
                $actionLogData = [
                    ActionLogInterface::ORDER_ID => (int)$orderId,
                    ActionLogInterface::RULE_ID => $rule->getRuleId(),
                    ActionLogInterface::STATUS => ActionStatus::ACTION_STATUS_SUCCESS
                ];
                $actionLog->setData($actionLogData);
            } catch (LocalizedException $e) {
                $errorMessage = 'There was an error while updating order status by order automation rule. '
                    . 'Rule ID: %1. Order ID: %2. Check the logs for more information.';
                $loggerMessage = "{$errorMessage} Message: %3.";
                $actionLogData = [
                    ActionLogInterface::ORDER_ID => (int)$orderId,
                    ActionLogInterface::RULE_ID => $rule->getRuleId(),
                    ActionLogInterface::STATUS => ActionStatus::ACTION_STATUS_ERROR,
                    ActionLogInterface::MESSAGE => __($errorMessage, $rule->getRuleId(), $orderId)->render()
                ];
                $actionLog->setData($actionLogData);
                $this->logger->error(
                    __($loggerMessage, $rule->getRuleId(), $orderId, $e->getMessage()),
                    ['exception' => $e]
                );
            }

            try {
                $actionLog = $this->actionLogRepository->save($actionLog);
                $this->afterActionRegistry->addActionLog($actionLog);
            } catch (CouldNotSaveException $e) {
                $this->logger->error(
                    __(
                        'Could not save action log entry for rule "%1" and order "%2". Message: %3',
                        $rule->getRuleId(),
                        $orderId,
                        $e->getMessage()
                    ),
                    [
                        'exception' => $e
                    ]
                );
            }
        }
    }

    /**
     * @param int $orderId
     * @param RuleInterface $rule
     * @return void
     * @throws InvalidArgumentException
     */
    private function updateOrderStatus(int $orderId, RuleInterface $rule): void
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
