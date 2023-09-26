<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface ActionLogInterface extends ExtensibleDataInterface
{
    public const ACTION_ID = 'action_id';
    public const ORDER_ID = 'order_id';
    public const RULE_ID = 'rule_id';
    public const STATUS = 'status';
    public const MESSAGE = 'message';
    public const CREATED_AT = 'created_at';

    /**
     * @return int|null
     */
    public function getActionId(): ?int;

    /**
     * @param int $actionId
     * @return void
     */
    public function setActionId(int $actionId): void;

    /**
     * @return int
     */
    public function getOrderId(): int;

    /**
     * @param int $orderId
     * @return void
     */
    public function setOrderId(int $orderId): void;

    /**
     * @return int
     */
    public function getRuleId(): int;

    /**
     * @param int $ruleId
     * @return void
     */
    public function setRuleId(int $ruleId): void;

    /**
     * @return string
     */
    public function getStatus(): string;

    /**
     * @param string $status
     * @return void
     */
    public function setStatus(string $status): void;

    /**
     * @return string|null
     */
    public function getMessage(): ?string;

    /**
     * @param string|null $message
     * @return void
     */
    public function setMessage(?string $message): void;

    /**
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * @param string $createdAt
     * @return void
     */
    public function setCreatedAt(string $createdAt): void;

    /**
     * @return \Niktar\OrderAutomation\Api\Data\ActionLogExtensionInterface
     */
    public function getExtensionAttributes(): \Niktar\OrderAutomation\Api\Data\ActionLogExtensionInterface;

    /**
     * @param \Niktar\OrderAutomation\Api\Data\ActionLogExtensionInterface $extensionAttributes
     * @return void
     */
    public function setExtensionAttributes(\Niktar\OrderAutomation\Api\Data\ActionLogExtensionInterface $extensionAttributes): void;
}
