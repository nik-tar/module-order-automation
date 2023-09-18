<?php
/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
/** @noinspection PhpFullyQualifiedNameUsageInspection */
declare(strict_types=1);

namespace Niktar\OrderAutomation\Api;

interface RuleRepositoryInterface
{
    /**
     * Create or update Order Automation Rule.
     *
     * @param \Niktar\OrderAutomation\Api\Data\RuleInterface $rule
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Niktar\OrderAutomation\Api\Data\RuleInterface $rule): void;

    /**
     * Get Order Automation Rule by ID
     *
     * @param int $id
     * @return \Niktar\OrderAutomation\Api\Data\RuleInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById(int $id): \Niktar\OrderAutomation\Api\Data\RuleInterface;

    /**
     * Retrieve Order Automation Rules which match a specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Niktar\OrderAutomation\Api\Data\RuleSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria): \Niktar\OrderAutomation\Api\Data\RuleSearchResultsInterface;

    /**
     * Delete Order Automation Rule
     *
     * @param \Niktar\OrderAutomation\Api\Data\RuleInterface $rule
     * @return void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Niktar\OrderAutomation\Api\Data\RuleInterface $rule): void;

    /**
     * Delete Order Automation Rule ID
     *
     * @param int $id
     * @return void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById(int $id): void;
}
