<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Cron;

use Niktar\OrderAutomation\Api\Data\RuleInterface;
use Niktar\OrderAutomation\Helper\Config as ConfigHelper;
use Niktar\OrderAutomation\Helper\Rule as RuleHelper;
use Niktar\OrderAutomation\Model\Action\ActionInterface;
use Niktar\OrderAutomation\Model\Action\Resolver;

class ProcessAutomationRules
{
    /**
     * @param RuleHelper $ruleHelper
     * @param ConfigHelper $configHelper
     * @param Resolver $actionResolver
     */
    public function __construct(
        private RuleHelper $ruleHelper,
        private ConfigHelper $configHelper,
        private Resolver $actionResolver
    ) {
    }

    /**
     * Job executes all available automation rules with limits
     *
     * @return void
     */
    public function execute(): void
    {
        if (!$this->configHelper->isEnabled()) {
            return;
        }
        $actionTypes = $this->ruleHelper->getAllAvailableActionTypes();

        foreach ($actionTypes as $actionType => $label) {
            $actionHandler = $this->actionResolver->resolve($actionType);
            if ($actionHandler === null) {
                continue;
            }
            $rules = $this->ruleHelper->getRulesByActionType($actionType);
            $this->processRules($rules, $actionHandler);
        }
    }

    /**
     * @param RuleInterface[] $rules
     * @param ActionInterface $actionHandler
     * @return void
     */
    private function processRules(array $rules, ActionInterface $actionHandler): void
    {
        foreach ($rules as $rule) {
            try {
                $actionHandler->execute($rule);
            } catch (\Exception $e) {
                // TODO: process exceptions, create error action log
                continue;
            }
        }
    }
}
