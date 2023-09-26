<?php

namespace Niktar\OrderAutomation\Helper;

use Niktar\OrderAutomation\Api\Data\RuleInterface;
use Niktar\OrderAutomation\Api\RuleRepositoryInterface;
use Niktar\OrderAutomation\Model\ResourceModel\ActionLog as ActionLogResource;
use Niktar\OrderAutomation\Ui\Source\ActionType;

class Rule
{
    /**
     * @param ActionType $actionTypeSource
     * @param RuleRepositoryInterface $ruleRepository
     * @param ActionLogResource $actionLogResource
     */
    public function __construct(
        private readonly ActionType $actionTypeSource,
        private readonly RuleRepositoryInterface $ruleRepository,
        private readonly ActionLogResource $actionLogResource
    ) {
    }

    /**
     * Get all available action types.
     * Key is action type's code, value is its label.
     *
     * @return array
     */
    public function getAllAvailableActionTypes(): array
    {
        return $this->actionTypeSource->toArray();
    }

    /**
     * @param string $actionType
     * @return RuleInterface[]
     */
    public function getRulesByActionType(string $actionType): array
    {
        return $this->ruleRepository->addActionTypeFilter($actionType)->getList()->getItems();
    }

    /**
     * @param RuleInterface $rule
     * @param int $limit
     * @param bool $excludeProcessed
     * @return int[]
     */
    public function getOrderIdsForRule(RuleInterface $rule, int $limit, bool $excludeProcessed = true): array
    {
        $connection = $this->actionLogResource->getConnection();
        $ruleId = $rule->getRuleId();
        $applyIn = $rule->getApplyIn();
        $orderStatus = $rule->getOrderStatus();
        // cron runs every 10 minutes
        $leftInterval = "INTERVAL '-{$applyIn}:10' HOUR_MINUTE";
        $rightInterval = "INTERVAL '-{$applyIn}' HOUR";

        $select = $connection->select()
            ->from(['so' => $connection->getTableName('sales_order')], ['so.entity_id'])
            ->where('so.status = ?', $orderStatus)
            ->where("so.created_at BETWEEN DATE_ADD(NOW(), {$leftInterval}) AND DATE_ADD(NOW(), {$rightInterval})")
            ->group('so.entity_id')
            ->limit($limit);

        if ($excludeProcessed) {
            $statusCondition = $connection->prepareSqlCondition('al.status', 'success');
            $ruleIdCondition = $connection->prepareSqlCondition('al.rule_id', $ruleId);
            $select->joinLeft(
                ['al' => $connection->getTableName('order_automation_action_log')],
                "so.entity_id = al.order_id AND {$ruleIdCondition} AND {$statusCondition}",
                []
            )->where('al.order_id IS NULL');
        }

        return $connection->fetchCol($select);
    }
}
