<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Ui\Source;

use Magento\Framework\Data\OptionSourceInterface;

class ActionType implements OptionSourceInterface
{
    public const ACTION_TYPE_SEND_EMAIL = 1;
    public const ACTION_TYPE_CHANGE_ORDER_STATUS = 2;
    public const ACTION_TYPE_ADD_ORDER_COMMENT = 3;

    /**
     * @var array
     */
    private array $options = [];

    /**
     * @inheritDoc
     */
    public function toOptionArray(): array
    {
        if (!empty($this->options)) {
            return $this->options;
        }

        $this->options = [
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
        return $this->options;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $optionArray = $this->toOptionArray();
        return array_reduce($optionArray, [$this, 'flattenOptionArrayItemCallback'], []);
    }

    /**
     * @param array $carry
     * @param array $item
     * @return array
     */
    private function flattenOptionArrayItemCallback(array $carry, array $item): array
    {
        if (empty($item['value'])) {
            return $carry;
        }
        $carry[$item['value']] = $item['label'];
        return $carry;
    }
}
