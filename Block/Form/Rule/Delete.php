<?php

namespace Niktar\OrderAutomation\Block\Form\Rule;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Niktar\OrderAutomation\Api\Data\OrderAutomationRuleInterface;

/**
 * Delete entity button.
 */
class Delete extends GenericButton implements ButtonProviderInterface
{
    /**
     * Retrieve Delete button settings.
     *
     * @return array
     */
    public function getButtonData(): array
    {
        if (!$this->getRuleId()) {
            return [];
        }

        return $this->wrapButtonSettings(
            __('Delete')->getText(),
            'delete',
            sprintf("deleteConfirm('%s', '%s')",
                __('Are you sure you want to delete this rule?'),
                $this->getUrl(
                    '*/*/delete',
                    [OrderAutomationRuleInterface::RULE_ID => $this->getRuleId()]
                )
            ),
            [],
            20
        );
    }
}
