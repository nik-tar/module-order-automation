<?php

namespace Niktar\OrderAutomation\Ui\Source;

use Magento\Framework\Data\OptionSourceInterface;

class ActionStatus implements OptionSourceInterface
{
    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'success', 'label' => __('Success')],
            ['value' => 'error', 'label' => __('Error')]
        ];
    }
}
