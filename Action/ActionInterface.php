<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Action;

use Niktar\OrderAutomation\Api\Data\RuleInterface;

interface ActionInterface
{
    /**
     * @param RuleInterface $rule
     * @return void
     */
    public function execute(RuleInterface $rule): void;
}
