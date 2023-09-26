<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface RuleSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get items list.
     *
     * @return \Niktar\OrderAutomation\Api\Data\RuleInterface[]
     */
    public function getItems(): array;

    /**
     * Set items list.
     *
     * @param \Niktar\OrderAutomation\Api\Data\RuleInterface[] $items
     * @return void
     */
    public function setItems(array $items): void;
}
