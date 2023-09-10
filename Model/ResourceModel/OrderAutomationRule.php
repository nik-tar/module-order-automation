<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Model\ResourceModel;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataInterface;
use Magento\Framework\Api\ObjectFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Reflection\DataObjectProcessor;
use Niktar\OrderAutomation\Api\Data\ActionDataInterface;
use Niktar\OrderAutomation\Api\Data\OrderAutomationRuleInterface;

class OrderAutomationRule extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'order_automation_rule_resource_model';

    /**
     * @inheritDoc
     */
    protected $_serializableFields = [
        OrderAutomationRuleInterface::ACTION_DATA => [
            [
                ActionDataInterface::EMAIL_TEMPLATE => null,
                ActionDataInterface::NEW_ORDER_STATUS => null,
                ActionDataInterface::COMMENT_TEXT => null,
                ActionDataInterface::IS_COMMENT_VISIBLE_ON_FRONT => false,
                ActionDataInterface::IS_CUSTOMER_NOTIFIED => false
            ],
            [
                ActionDataInterface::EMAIL_TEMPLATE => null,
                ActionDataInterface::NEW_ORDER_STATUS => null,
                ActionDataInterface::COMMENT_TEXT => null,
                ActionDataInterface::IS_COMMENT_VISIBLE_ON_FRONT => false,
                ActionDataInterface::IS_CUSTOMER_NOTIFIED => false
            ],
            false
        ]
    ];

    /**
     * By default, if we add fields to $_serializableFields they serialize to array,
     * but I wanted to have data classes instead of simple arrays
     * @see \Niktar\OrderAutomation\Model\ResourceModel\OrderAutomationRule::_serializeField
     * @see \Niktar\OrderAutomation\Model\ResourceModel\OrderAutomationRule::_unserializeField
     */
    const TYPED_FIELDS = [
        OrderAutomationRuleInterface::ACTION_DATA => ActionDataInterface::class
    ];

    /**
     * @param Context $context
     * @param DataObjectProcessor $dataObjectProcessor
     * @param DataObjectHelper $dataObjectHelper
     * @param ObjectFactory $objectFactory
     * @param $connectionName
     */
    public function __construct(
        Context $context,
        private DataObjectProcessor $dataObjectProcessor,
        private DataObjectHelper $dataObjectHelper,
        private ObjectFactory $objectFactory,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
    }

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            'order_automation_rule',
            OrderAutomationRuleInterface::RULE_ID
        );
    }


    /**
     * @inheritDoc
     */
    protected function _serializeField(DataObject $object, $field, $defaultValue = null, $unsetEmpty = false)
    {
        if (!isset(self::TYPED_FIELDS[$field])) {
            return parent::_serializeField($object, $field, $defaultValue, $unsetEmpty);
        }

        /** @var ExtensibleDataInterface[] $value */
        $value = $object->getData($field) ?: [];

        $objectClass = self::TYPED_FIELDS[$field];

        $newValue = $this->dataObjectProcessor->buildOutputDataArray(
            $value,
            $objectClass
        );

        $object->setData($field, $newValue);

        return parent::_serializeField($object, $field, $defaultValue, $unsetEmpty);
    }


    /**
     * @inheritDoc
     */
    protected function _unserializeField(DataObject $object, $field, $defaultValue = null)
    {
        parent::_unserializeField($object, $field, $defaultValue);

        if (!isset(self::TYPED_FIELDS[$field])) {
            return;
        }

        /** @var array[] $value */
        $value = $object->getData($field) ?: [];

        $objectClass = self::TYPED_FIELDS[$field];

        $newValue = $this->objectFactory->create($objectClass, []);
        $this->dataObjectHelper->populateWithArray(
            $newValue,
            $value,
            $objectClass
        );

        $object->setData($field, $newValue);
    }
}
