<?php

namespace Niktar\OrderAutomation\Plugin;

use Niktar\OrderAutomation\Action\ActionInterface;
use Niktar\OrderAutomation\Action\GenerateComment;
use Niktar\OrderAutomation\Registry\AfterAction;

class AddOrderCommentAfterAction
{
    /**
     * @param AfterAction $afterActionRegistry
     * @param GenerateComment $generateComment
     */
    public function __construct(
        private readonly AfterAction $afterActionRegistry,
        private readonly GenerateComment $generateComment
    ) {
    }

    public function afterExecute(ActionInterface $subject, $result)
    {
        $actionLogs = $this->afterActionRegistry->getActionLogs();
        foreach ($actionLogs as $actionLog) {
            $comment = __(
                'Order Automation Rule #%1 was processed on this order with "%2" status.'
                . (empty($actionLog->getMessage()) ? '' : "\n\nMessage: %3."),
                $actionLog->getRuleId(),
                $actionLog->getStatus(),
                $actionLog->getMessage()
            );
            $this->generateComment->execute($comment, $actionLog->getOrderId(), null, false);
        }
        $this->afterActionRegistry->reset();
        return null;
    }
}
