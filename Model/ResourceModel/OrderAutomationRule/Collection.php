<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Model\ResourceModel\OrderAutomationRule;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Niktar\OrderAutomation\Model\OrderAutomationRule as OrderAutomationRuleModel;
use Niktar\OrderAutomation\Model\ResourceModel\OrderAutomationRule as OrderAutomationRuleResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'order_automation_rule_collection';

    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init(
            OrderAutomationRuleModel::class,
            OrderAutomationRuleResourceModel::class
        );
    }

}
