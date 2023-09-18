<?php

namespace Niktar\OrderAutomation\Ui\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Sales\Model\Order\Config;

class OrderStatus implements OptionSourceInterface
{
    /**
     * @var string[]
     */
    private array $stateStatuses = [
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
        $statuses = $this->stateStatuses
            ? $this->orderConfig->getStateStatuses($this->stateStatuses)
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
