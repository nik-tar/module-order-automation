<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Model\Action;

use Niktar\OrderAutomation\Api\Data\RuleInterface;
use Niktar\OrderAutomation\Helper\Config;

class AddOrderComment implements ActionInterface
{
    /**
     * @param Config $config
     */
    public function __construct(
        private Config $config
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
