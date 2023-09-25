<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Model;

use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Niktar\OrderAutomation\Api\Data\ActionLogInterfaceFactory as ModelFactory;
use Niktar\OrderAutomation\Api\Data\ActionLogInterface as ModelInterface;
use Niktar\OrderAutomation\Api\Data\ActionLogSearchResultsInterface as SearchResultsInterface;
use Niktar\OrderAutomation\Api\Data\ActionLogSearchResultsInterfaceFactory as SearchResultsFactory;
use Niktar\OrderAutomation\Api\ActionLogRepositoryInterface;
use Niktar\OrderAutomation\Model\ActionLog as Model;
use Niktar\OrderAutomation\Model\ResourceModel\ActionLog as ResourceModel;
use Niktar\OrderAutomation\Model\ResourceModel\ActionLog\Collection as Collection;
use Niktar\OrderAutomation\Model\ResourceModel\ActionLog\CollectionFactory as CollectionFactory;

class ActionLogRepository implements ActionLogRepositoryInterface
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
    public function save(ModelInterface $actionLog): ModelInterface
    {
        try {
            $model = $this->modelFactory->create();
            $model->addData($actionLog->getData());
            $model->setHasDataChanges(true);

            if (!$model->getData(ModelInterface::ACTION_ID)) {
                $model->isObjectNew(true);
            }
            $this->resourceModel->save($model);
            return $model;
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __(
                    'Could not save the Order Automation Action Log: %1',
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
        /** @var Model $actionLog */
        $actionLog = $this->modelFactory->create();
        $this->resourceModel->load($actionLog, $id);
        if (!$actionLog->getData(ModelInterface::ACTION_ID)) {
            throw new NoSuchEntityException(__('Order Automation Action Log with id "%1" does not exist.', $id));
        }
        return $actionLog;
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
    public function delete(ModelInterface $actionLog): void
    {
        try {
            /** @var Model $actionLog */
            $this->resourceModel->delete($actionLog);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __(
                    'Could not delete the Order Automation Action Log: %1',
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
