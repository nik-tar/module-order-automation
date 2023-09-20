<?php

namespace Niktar\OrderAutomation\Helper;

use Niktar\OrderAutomation\Api\Data\RuleInterface;
use Niktar\OrderAutomation\Api\RuleRepositoryInterface;
use Niktar\OrderAutomation\Ui\Source\ActionType;

class Rule
{
    /**
     * @param ActionType $actionTypeSource
     */
    public function __construct(
        private ActionType $actionTypeSource,
        private RuleRepositoryInterface $ruleRepository
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
     * @param int $actionType
     * @return RuleInterface[]
     */
    public function getRulesByActionType(int $actionType): array
    {
        return $this->ruleRepository->addActionTypeFilter($actionType)->getList()->getItems();
    }
}
