<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Helper;

use Niktar\OrderAutomation\Api\Data\ActionDataInterface;
use Niktar\OrderAutomation\Ui\Source\ActionType;
use Niktar\OrderAutomation\Ui\Source\OrderStatus;

class ActionData
{
    private const KEY_TO_READABLE_FORMAT_MAP = [
        ActionDataInterface::ACTION_TYPE => 'Action Type',
        ActionDataInterface::EMAIL_TEMPLATE => 'Email Template',
        ActionDataInterface::NEW_ORDER_STATUS => 'New Order Status',
        ActionDataInterface::COMMENT_TEXT => 'Comment Text',
        ActionDataInterface::IS_COMMENT_VISIBLE_ON_FRONT => 'Is Comment Visible on Front',
        ActionDataInterface::IS_CUSTOMER_NOTIFIED => 'Is Customer Notified'
    ];

    private const VISIBLE_BY_ACTION_TYPE = [
        [], // filler
        ActionType::ACTION_TYPE_SEND_EMAIL => [
            ActionDataInterface::EMAIL_TEMPLATE
        ],
        ActionType::ACTION_TYPE_CHANGE_ORDER_STATUS => [
            ActionDataInterface::NEW_ORDER_STATUS
        ],
        ActionType::ACTION_TYPE_ADD_ORDER_COMMENT => [
            ActionDataInterface::COMMENT_TEXT,
            ActionDataInterface::IS_COMMENT_VISIBLE_ON_FRONT,
            ActionDataInterface::IS_CUSTOMER_NOTIFIED
        ]
    ];

    /**
     * @var string[]
     */
    private array $keyToReadableFormatMap;

    /**
     * @var array
     */
    private array $visibleByActionType;

    /**
     * @param ActionType $actionTypeSource
     * @param OrderStatus $orderStatusSource
     * @param array $keyToReadableFormatMap
     * @param array $visibleByActionType
     */
    public function __construct(
        private ActionType $actionTypeSource,
        private OrderStatus $orderStatusSource,
        array $keyToReadableFormatMap = [],
        array $visibleByActionType = []
    ) {
        $this->keyToReadableFormatMap = [...self::KEY_TO_READABLE_FORMAT_MAP, ...$keyToReadableFormatMap];
        $this->visibleByActionType = [...self::VISIBLE_BY_ACTION_TYPE, ...$visibleByActionType];
    }


    /**
     * Returns array for frontend representation.
     *
     * @param array $actionData
     * @return array
     */
    public function getFrontendRepresentation(array $actionData): array
    {
        $representedData = [];
        if (empty($actionData)) {
            return $representedData;
        }
        $actionData = $this->filterDataByActionType($actionData);
        foreach ($actionData as $key => $value) {
            if (!isset($this->keyToReadableFormatMap[$key])) {
                continue;
            }
            $readableFieldName = __($this->keyToReadableFormatMap[$key]);
            $readableFieldValue = $this->getReadableFieldValue($key, $value);
            $representedData[] = [
                'label' => $readableFieldName,
                'value' => $readableFieldValue
            ];
        }
        return $representedData;
    }

    /**
     * @param int|string $key
     * @param mixed $value
     * @return string
     */
    private function getReadableFieldValue(int|string $key, mixed $value): string
    {
        switch ($key) {
            case ActionDataInterface::EMAIL_TEMPLATE:
                return 'Empty';
            case ActionDataInterface::ACTION_TYPE:
                $options = $this->actionTypeSource->toArray();
                return (string)($options[$value] ?? 'Empty');
            case ActionDataInterface::NEW_ORDER_STATUS:
                $options = $this->orderStatusSource->toArray();
                return (string)($options[$value] ?? 'Empty');
            case ActionDataInterface::IS_COMMENT_VISIBLE_ON_FRONT:
            case ActionDataInterface::IS_CUSTOMER_NOTIFIED:
                return $value === true ? 'Yes' : 'No';
            default:
                return $value;
        }
    }

    /**
     * @param array $actionData
     * @return array
     */
    private function filterDataByActionType(array $actionData): array
    {
        $actionType = $actionData[ActionDataInterface::ACTION_TYPE] ?? null;
        if ($actionType !== null && isset($this->visibleByActionType[$actionType])) {
            $visibleDataKeys = [
                ...$this->visibleByActionType[$actionData[ActionDataInterface::ACTION_TYPE]],
                ActionDataInterface::ACTION_TYPE
            ];
            $actionData = array_filter(
                $actionData,
                fn ($key) => in_array($key, $visibleDataKeys),
                ARRAY_FILTER_USE_KEY
            );
        }
        return $actionData;
    }
}
