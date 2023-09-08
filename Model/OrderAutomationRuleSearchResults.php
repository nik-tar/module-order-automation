<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Model;

use Magento\Framework\Api\SearchResults;
use Niktar\OrderAutomation\Api\Data\OrderAutomationRuleSearchResultsInterface;

class OrderAutomationRuleSearchResults extends SearchResults implements OrderAutomationRuleSearchResultsInterface
{
    /**
     * Get items list.
     *
     * @return \Niktar\OrderAutomation\Api\Data\OrderAutomationRuleInterface[]
     */
    public function getItems(): array
    {
        return parent::getItems();
    }

    /**
     * Set items list.
     *
     * @param \Niktar\OrderAutomation\Api\Data\OrderAutomationRuleInterface[] $items
     * @return void
     */
    public function setItems(array $items): void
    {
        parent::setItems($items);
    }
}
