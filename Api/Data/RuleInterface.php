<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface RuleInterface extends ExtensibleDataInterface
{
    public const RULE_ID = 'rule_id';
    public const PAYMENT_METHOD = 'payment_method';
    public const APPLY_IN = 'apply_in';
    public const ORDER_STATUS = 'order_status';
    public const ACTION_DATA = 'action_data';

    /**
     * @return int|null
     */
    public function getRuleId(): ?int;

    /**
     * @param int $ruleId
     * @return void
     */
    public function setRuleId(int $ruleId): void;

    /**
     * @return string
     */
    public function getPaymentMethod(): string;

    /**
     * @param string $paymentMethod
     * @return void
     */
    public function setPaymentMethod(string $paymentMethod): void;

    /**
     * @return int
     */
    public function getApplyIn(): int;

    /**
     * @param int $applyIn
     * @return void
     */
    public function setApplyIn(int $applyIn): void;

    /**
     * @return string
     */
    public function getOrderStatus(): string;

    /**
     * @param string $orderStatus
     * @return void
     */
    public function setOrderStatus(string $orderStatus): void;

    /**
     * @return \Niktar\OrderAutomation\Api\Data\ActionDataInterface
     */
    public function getActionData(): ActionDataInterface;

    /**
     * @param \Niktar\OrderAutomation\Api\Data\ActionDataInterface $actionData
     * @return void
     */
    public function setActionData(ActionDataInterface $actionData): void;

    /**
     * @return \Niktar\OrderAutomation\Api\Data\RuleExtensionInterface
     */
    public function getExtensionAttributes(): \Niktar\OrderAutomation\Api\Data\RuleExtensionInterface;

    /**
     * @param \Niktar\OrderAutomation\Api\Data\RuleExtensionInterface $extensionAttributes
     * @return void
     */
    public function setExtensionAttributes(\Niktar\OrderAutomation\Api\Data\RuleExtensionInterface $extensionAttributes): void;
}
