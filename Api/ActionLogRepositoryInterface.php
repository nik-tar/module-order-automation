<?php
/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
/** @noinspection PhpFullyQualifiedNameUsageInspection */
declare(strict_types=1);

namespace Niktar\OrderAutomation\Api;

interface ActionLogRepositoryInterface
{
    /**
     * Create or update Order Automation Action Log.
     *
     * @param \Niktar\OrderAutomation\Api\Data\ActionLogInterface $actionLog
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Niktar\OrderAutomation\Api\Data\ActionLogInterface $actionLog): void;

    /**
     * Get Order Automation Action Log by ID
     *
     * @param int $id
     * @return \Niktar\OrderAutomation\Api\Data\ActionLogInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById(int $id): \Niktar\OrderAutomation\Api\Data\ActionLogInterface;

    /**
     * Retrieve Order Automation Action Logs which match a specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Niktar\OrderAutomation\Api\Data\ActionLogSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria): \Niktar\OrderAutomation\Api\Data\ActionLogSearchResultsInterface;

    /**
     * Delete Order Automation Action Log
     *
     * @param \Niktar\OrderAutomation\Api\Data\ActionLogInterface $actionLog
     * @return void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Niktar\OrderAutomation\Api\Data\ActionLogInterface $actionLog): void;

    /**
     * Delete Order Automation Action Log ID
     *
     * @param int $id
     * @return void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById(int $id): void;
}
