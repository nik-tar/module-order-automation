<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use Niktar\OrderAutomation\Api\Data\ActionDataInterface;
use Niktar\OrderAutomation\Api\Data\ActionLogInterface;
use Niktar\OrderAutomation\Api\Data\RuleInterface;

class ActionLog extends AbstractExtensibleModel implements ActionLogInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'order_automation_action_log_model';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            ResourceModel\ActionLog::class
        );
    }

    /**
     * @inheritDoc
     */
    public function getActionId(): ?int
    {
        return $this->getData(self::ACTION_ID) === null ? null
            : (int)$this->getData(self::ACTION_ID);
    }

    /**
     * @inheritDoc
     */
    public function setActionId(int $actionId): void
    {
        $this->setData(self::ACTION_ID, $actionId);
    }

    /**
     * @inheritDoc
     */
    public function getOrderId(): int
    {
        return $this->getData(self::ORDER_ID);
    }

    /**
     * @inheritDoc
     */
    public function setOrderId(int $orderId): void
    {
        $this->setData(self::ORDER_ID, $orderId);
    }

    /**
     * @inheritDoc
     */
    public function getRuleId(): int
    {
        return $this->getData(self::RULE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setRuleId(int $ruleId): void
    {
        $this->setData(self::RULE_ID, $ruleId);
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): string
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus(string $status): void
    {
        $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): ?string
    {
        return $this->getData(self::MESSAGE);
    }

    /**
     * @inheritDoc
     */
    public function setMessage(?string $message): void
    {
        $this->setData(self::MESSAGE, $message);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt(): string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * @inheritDoc
     */
    public function getExtensionAttributes(): \Niktar\OrderAutomation\Api\Data\ActionLogExtensionInterface
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * @inheritDoc
     */
    public function setExtensionAttributes(\Niktar\OrderAutomation\Api\Data\ActionLogExtensionInterface $extensionAttributes): void
    {
        $this->_setExtensionAttributes($extensionAttributes);
    }
}
