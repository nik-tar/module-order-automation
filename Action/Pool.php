<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Action;

class Pool
{
    /**
     * @param ActionInterface[] $actions
     */
    public function __construct(
        private readonly array $actions = []
    ) {
    }

    /**
     * @param string $actionType
     * @return ActionInterface
     */
    public function getAction(string $actionType): ActionInterface
    {
        if (!isset($this->actions[$actionType])) {
            throw new \InvalidArgumentException('Unknown action type ' . $actionType);
        }
        return $this->actions[$actionType];
    }
}
