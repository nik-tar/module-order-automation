<?php

namespace Niktar\OrderAutomation\Ui\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Sales\Model\Order\Config;

class OrderStatus implements OptionSourceInterface
{
    public const STATE_STATUSES = [
        \Magento\Sales\Model\Order::STATE_COMPLETE,
        \Magento\Sales\Model\Order::STATE_CANCELED,
        \Magento\Sales\Model\Order::STATE_HOLDED,
    ];

    /**
     * @param Config $orderConfig
     */
    public function __construct(
        private Config $orderConfig
    ) {
    }

    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        $statuses = self::STATE_STATUSES
            ? $this->orderConfig->getStateStatuses(self::STATE_STATUSES)
            : $this->orderConfig->getStatuses();

        $options = [['value' => '', 'label' => __('-- Please Select --')]];
        foreach ($statuses as $code => $label) {
            $options[] = ['value' => $code, 'label' => $label];
        }
        return $options;
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
