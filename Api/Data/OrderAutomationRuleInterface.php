<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface OrderAutomationRuleInterface extends ExtensibleDataInterface
{
    public const RULE_ID = 'rule_id';
    public const PAYMENT_METHOD = 'payment_method';
    public const APPLY_IN = 'apply_in';
    public const ORDER_STATUS = 'order_status';
    public const ACTION_TYPE = 'action_type';
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
     * @return int
     */
    public function getActionType(): int;

    /**
     * @param int $actionType
     * @return void
     */
    public function setActionType(int $actionType): void;

    /**
     * @return string
     */
    public function getActionData(): string;

    /**
     * @param string $actionData
     * @return void
     */
    public function setActionData(string $actionData): void;

    /**
     * @return \Niktar\OrderAutomation\Api\Data\OrderAutomationRuleExtensionInterface
     */
    public function getExtensionAttributes(): \Niktar\OrderAutomation\Api\Data\OrderAutomationRuleExtensionInterface;

    /**
     * @param \Niktar\OrderAutomation\Api\Data\OrderAutomationRuleExtensionInterface $extensionAttributes
     * @return void
     */
    public function setExtensionAttributes(\Niktar\OrderAutomation\Api\Data\OrderAutomationRuleExtensionInterface $extensionAttributes): void;
}
