<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Action;

use Niktar\OrderAutomation\Api\Data\RuleInterface;
use Niktar\OrderAutomation\Helper\Config;

class SendEmail implements ActionInterface
{
    /**
     * @param Config $config
     */
    public function __construct(
        private readonly Config $config
    ) {
    }

    /**
     * @inheritDoc
     */
    public function execute(RuleInterface $rule): void
    {
        // TODO: Implement execute() method.
    }

}
