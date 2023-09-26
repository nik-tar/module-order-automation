<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface ActionLogSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get items list.
     *
     * @return \Niktar\OrderAutomation\Api\Data\ActionLogInterface[]
     */
    public function getItems(): array;

    /**
     * Set items list.
     *
     * @param \Niktar\OrderAutomation\Api\Data\ActionLogInterface[] $items
     * @return void
     */
    public function setItems(array $items): void;
}
