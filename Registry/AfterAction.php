<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Registry;

use Niktar\OrderAutomation\Api\Data\ActionLogInterface;

class AfterAction
{
    /**
     * @var ActionLogInterface[]
     */
    private array $actionLogs = [];

    /**
     * @param ActionLogInterface $actionLog
     * @return void
     */
    public function addActionLog(ActionLogInterface $actionLog): void
    {
        $this->actionLogs[] = $actionLog;
    }

    /**
     * @return ActionLogInterface[]
     */
    public function getActionLogs(): array
    {
        return $this->actionLogs;
    }

    /**
     * @return void
     */
    public function reset(): void
    {
        $this->actionLogs = [];
    }
}
