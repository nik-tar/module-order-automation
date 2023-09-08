<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Niktar\OrderAutomation\Api\Data\OrderAutomationRuleInterface;

class OrderAutomationRule extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'order_automation_rule_resource_model';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            'order_automation_rule',
            OrderAutomationRuleInterface::RULE_ID
        );
    }
}
