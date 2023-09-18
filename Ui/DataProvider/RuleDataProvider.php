<?php

namespace Niktar\OrderAutomation\Ui\DataProvider;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Niktar\OrderAutomation\Api\Data\ActionDataInterface;
use Niktar\OrderAutomation\Api\Data\OrderAutomationRuleInterface as ModelInterface;
use Niktar\OrderAutomation\Model\OrderAutomationRule;
use Niktar\OrderAutomation\Model\ResourceModel\OrderAutomationRule\CollectionFactory;

/**
 * DataProvider component.
 */
class RuleDataProvider extends \Magento\Ui\DataProvider\ModifierPoolDataProvider
{
    /**
     * @var array
     */
    private $loadedData = [];

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param DataObjectProcessor $dataObjectProcessor
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        private DataPersistorInterface $dataPersistor,
        private DataObjectProcessor $dataObjectProcessor,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data,
            $pool
        );
    }

    /**
     * Get data.
     *
     * @return array
     */
    public function getData(): array
    {
        if ($this->loadedData) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        /** @var OrderAutomationRule $rule */
        foreach ($items as $rule) {
            $ruleData = $this->dataObjectProcessor->buildOutputDataArray(
                $rule,
                ModelInterface::class
            );
            $this->loadedData[$rule->getId()] = $this->extractActionData($ruleData);
        }

        $data = $this->dataPersistor->get('niktar_order_automation_rule_form');
        if (!empty($data)) {
            $rule = $this->collection->getNewEmptyItem();
            $rule->setData($data);
            $ruleData = $rule->getData();
            $this->loadedData[$rule->getId()] = $this->extractActionData($ruleData);
            $this->dataPersistor->clear('niktar_order_automation_rule_form');
        }

        return $this->loadedData;
    }

    /**
     * Extract action data to fill its fields in rule edit form.
     *
     * @param array $ruleData
     * @return array
     */
    private function extractActionData(array $ruleData): array
    {
        $actionData = $ruleData[ModelInterface::ACTION_DATA] ?? null;
        if ($actionData === null) {
            return $ruleData;
        }
        if ($actionData instanceof ActionDataInterface) {
            $actionData = $actionData->__toArray();
        }
        if (!is_array($actionData)) {
            return $ruleData;
        }
        return [...$ruleData, ...$actionData];
    }
}
