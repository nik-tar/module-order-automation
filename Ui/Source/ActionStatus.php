<?php

namespace Niktar\OrderAutomation\Ui\Source;

use Magento\Framework\Data\OptionSourceInterface;

class ActionStatus implements OptionSourceInterface
{
    public const ACTION_STATUS_SUCCESS = 'success';
    public const ACTION_STATUS_ERROR = 'error';

    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::ACTION_STATUS_SUCCESS, 'label' => __('Success')],
            ['value' => self::ACTION_STATUS_ERROR, 'label' => __('Error')]
        ];
    }
}
