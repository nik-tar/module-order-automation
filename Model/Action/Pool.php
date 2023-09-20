<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Model\Action;

class Pool
{
    /**
     * @param ActionInterface[] $actions
     */
    public function __construct(
        private array $actions = []
    ) {
    }

    /**
     * @param int $actionType
     * @return ActionInterface
     * @throws \InvalidArgumentException
     */
    public function getAction(int $actionType): ActionInterface
    {
        if (!isset($this->actions[$actionType])) {
            throw new \InvalidArgumentException('Unknown action type ' . $actionType);
        }
        return $this->actions[$actionType];
    }
}
