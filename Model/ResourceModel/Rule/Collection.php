<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Model\ResourceModel\Rule;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Niktar\OrderAutomation\Model\Rule as OrderAutomationRuleModel;
use Niktar\OrderAutomation\Model\ResourceModel\Rule as OrderAutomationRuleResourceModel;

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

    /**
     * Unserialize fields in each item
     *
     * @return $this
     */
    protected function _afterLoad(): self
    {
        foreach ($this->_items as $item) {
            if ($item instanceof OrderAutomationRuleModel) {
                $this->getResource()->unserializeFields($item);
            }
        }
        return parent::_afterLoad();
    }

}
