<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Model;

use Magento\Framework\Api\SearchResults;
use Niktar\OrderAutomation\Api\Data\RuleSearchResultsInterface;

class ActionLogSearchResults extends SearchResults implements RuleSearchResultsInterface
{
    /**
     * Get items list.
     *
     * @return \Niktar\OrderAutomation\Api\Data\ActionLogInterface[]
     */
    public function getItems(): array
    {
        return parent::getItems();
    }

    /**
     * Set items list.
     *
     * @param \Niktar\OrderAutomation\Api\Data\ActionLogInterface[] $items
     * @return void
     */
    public function setItems(array $items): void
    {
        parent::setItems($items);
    }
}
