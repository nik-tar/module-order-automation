<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Ui\Source;

use Magento\Framework\Data\OptionSourceInterface;

class ActionType implements OptionSourceInterface
{
    public const ACTION_TYPE_SEND_EMAIL = 'send_email';
    public const ACTION_TYPE_CHANGE_ORDER_STATUS = 'change_order_status';
    public const ACTION_TYPE_ADD_ORDER_COMMENT = 'add_order_comment';

    /**
     * @inheritDoc
     */
    public function toOptionArray(): array
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
        if (empty($item['value']) && !is_string($item['value'])) {
            return $carry;
        }
        $carry[$item['value']] = $item['label'];
        return $carry;
    }
}
