<?php

namespace Niktar\OrderAutomation\Ui\Source;

use Magento\Framework\Data\OptionSourceInterface;

class ActionType implements OptionSourceInterface
{
    public const ACTION_TYPE_SEND_EMAIL = 1;
    public const ACTION_TYPE_CHANGE_ORDER_STATUS = 2;
    public const ACTION_TYPE_ADD_ORDER_COMMENT = 3;

    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::ACTION_TYPE_SEND_EMAIL,
                'label' => __('Send Email to Customer')
            ],
            [
                'value' => self::ACTION_TYPE_CHANGE_ORDER_STATUS,
                'label' => __('Change Order Status')
            ],
            [
                'value' => self::ACTION_TYPE_ADD_ORDER_COMMENT,
                'label' => __('Add Order Comment')
            ]
        ];
    }
}
