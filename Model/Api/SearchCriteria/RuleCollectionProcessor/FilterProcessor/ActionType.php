<?php

namespace Niktar\OrderAutomation\Model\Api\SearchCriteria\RuleCollectionProcessor\FilterProcessor;

use Magento\Framework\Api\Filter;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor\FilterProcessor\CustomFilterInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Niktar\OrderAutomation\Model\ResourceModel\Rule\Collection;

class ActionType implements CustomFilterInterface
{
    /**
     * @inheritDoc
     */
    public function apply(Filter $filter, AbstractDb $collection)
    {
        /** @var Collection $collection */
        $collection->addActionTypeFilter($filter->getValue());
        return true;
    }

}
