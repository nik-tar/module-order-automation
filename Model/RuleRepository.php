<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Model;

use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Niktar\OrderAutomation\Api\Data\RuleInterfaceFactory as ModelFactory;
use Niktar\OrderAutomation\Api\Data\RuleInterface as ModelInterface;
use Niktar\OrderAutomation\Api\Data\RuleSearchResultsInterface as SearchResultsInterface;
use Niktar\OrderAutomation\Api\Data\RuleSearchResultsInterfaceFactory as SearchResultsFactory;
use Niktar\OrderAutomation\Api\RuleRepositoryInterface;
use Niktar\OrderAutomation\Model\Rule as Model;
use Niktar\OrderAutomation\Model\ResourceModel\Rule as ResourceModel;
use Niktar\OrderAutomation\Model\ResourceModel\Rule\Collection as Collection;
use Niktar\OrderAutomation\Model\ResourceModel\Rule\CollectionFactory as CollectionFactory;

class RuleRepository implements RuleRepositoryInterface
{
    /**
     * @param ResourceModel $resourceModel
     * @param ModelFactory $modelFactory
     * @param CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $joinProcessor
     * @param SearchResultsFactory $searchResultsFactory
     */
    public function __construct(
        private ResourceModel $resourceModel,
        private ModelFactory $modelFactory,
        private CollectionFactory $collectionFactory,
        private CollectionProcessorInterface $collectionProcessor,
        private JoinProcessorInterface $joinProcessor,
        private SearchResultsFactory $searchResultsFactory
    ) {
    }

    /**
     * @inheritDoc
     */
    public function save(ModelInterface $rule): void
    {
        try {
            $model = $this->modelFactory->create();
            $model->addData($rule->getData());
            $model->setHasDataChanges(true);

            if (!$model->getData(ModelInterface::RULE_ID)) {
                $model->isObjectNew(true);
            }
            $this->resourceModel->save($model);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __(
                    'Could not save the Order Automation Rule: %1',
                    $exception->getMessage()
                )
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): ModelInterface
    {
        /** @var Model $rule */
        $rule = $this->modelFactory->create();
        $this->resourceModel->load($rule, $id);
        if (!$rule->getData(ModelInterface::RULE_ID)) {
            throw new NoSuchEntityException(__('Order Automation Rule with id "%1" does not exist.', $id));
        }
        return $rule;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $criteria): SearchResultsInterface
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $this->joinProcessor->process(
            $collection,
            ModelInterface::class
        );
        $this->collectionProcessor->process($criteria, $collection);

        /** @var SearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(ModelInterface $rule): void
    {
        try {
            /** @var Model $rule */
            $this->resourceModel->delete($rule);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __(
                    'Could not delete the Order Automation Rule: %1',
                    $exception->getMessage()
                )
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int $id): void
    {
        $this->delete($this->getById($id));
    }
}
