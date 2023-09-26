<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Action;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Niktar\OrderAutomation\Api\ActionLogRepositoryInterface;
use Niktar\OrderAutomation\Api\Data\ActionLogInterface;
use Niktar\OrderAutomation\Api\Data\ActionLogInterfaceFactory;
use Niktar\OrderAutomation\Api\Data\RuleInterface;
use Niktar\OrderAutomation\Helper\Config;
use Niktar\OrderAutomation\Helper\Rule as RuleHelper;
use Niktar\OrderAutomation\Registry\AfterAction;
use Niktar\OrderAutomation\Ui\Source\ActionStatus;
use Psr\Log\LoggerInterface;

abstract class Action implements ActionInterface
{
    protected const ERROR_MESSAGE_BASE = 'There was an error while processing order automation rule. ';

    /**
     * @param Config $config
     * @param RuleHelper $ruleHelper
     * @param ActionLogInterfaceFactory $actionLogFactory
     * @param ActionLogRepositoryInterface $actionLogRepository
     * @param AfterAction $afterActionRegistry
     * @param LoggerInterface $logger
     */
    public function __construct(
        protected readonly Config $config,
        protected readonly RuleHelper $ruleHelper,
        protected readonly ActionLogInterfaceFactory $actionLogFactory,
        protected readonly ActionLogRepositoryInterface $actionLogRepository,
        protected readonly AfterAction $afterActionRegistry,
        protected readonly LoggerInterface $logger
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
                $this->executeAction($orderId, $rule);
                $actionLogData = [
                    ActionLogInterface::ORDER_ID => (int)$orderId,
                    ActionLogInterface::RULE_ID => $rule->getRuleId(),
                    ActionLogInterface::STATUS => ActionStatus::ACTION_STATUS_SUCCESS
                ];
                $actionLog->setData($actionLogData);
            } catch (LocalizedException $e) {
                $errorMessage = static::ERROR_MESSAGE_BASE
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
     * Main logic of the rule.
     *
     * @param int $orderId
     * @param RuleInterface $rule
     * @return void
     * @throws LocalizedException
     */
    protected function executeAction(int $orderId, RuleInterface $rule): void
    {
        throw new LocalizedException(__(
            'Main action logic is not defined. Rule ID: %1. Action type: "%2"',
            $rule->getRuleId(),
            $rule->getActionData()->getActionType()
        ));
    }
}
