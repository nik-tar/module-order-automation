<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use Niktar\OrderAutomation\Api\Data\ActionDataInterface;
use Niktar\OrderAutomation\Api\Data\OrderAutomationRuleInterface;

class OrderAutomationRule extends AbstractExtensibleModel implements OrderAutomationRuleInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'order_automation_rule_model';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            ResourceModel\OrderAutomationRule::class
        );
    }

    /**
     * @inheritDoc
     */
    public function getRuleId(): ?int
    {
        return $this->getData(self::RULE_ID) === null ? null
            : (int)$this->getData(self::RULE_ID);
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
    public function getPaymentMethod(): string
    {
        return $this->getData(self::PAYMENT_METHOD);
    }

    /**
     * @inheritDoc
     */
    public function setPaymentMethod(string $paymentMethod): void
    {
        $this->setData(self::PAYMENT_METHOD, $paymentMethod);
    }

    /**
     * @inheritDoc
     */
    public function getApplyIn(): int
    {
        return (int)$this->getData(self::APPLY_IN);
    }

    /**
     * @inheritDoc
     */
    public function setApplyIn(int $applyIn): void
    {
        $this->setData(self::APPLY_IN, $applyIn);
    }

    /**
     * @inheritDoc
     */
    public function getOrderStatus(): string
    {
        return $this->getData(self::ORDER_STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setOrderStatus(string $orderStatus): void
    {
        $this->setData(self::ORDER_STATUS, $orderStatus);
    }

    /**
     * @inheritDoc
     */
    public function getActionData(): ActionDataInterface
    {
        return $this->getData(self::ACTION_DATA);
    }

    /**
     * @inheritDoc
     */
    public function setActionData(ActionDataInterface $actionData): void
    {
        $this->setData(self::ACTION_DATA, $actionData);
    }

    /**
     * @inheritDoc
     */
    public function getExtensionAttributes(): \Niktar\OrderAutomation\Api\Data\OrderAutomationRuleExtensionInterface
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * @inheritDoc
     */
    public function setExtensionAttributes(\Niktar\OrderAutomation\Api\Data\OrderAutomationRuleExtensionInterface $extensionAttributes): void
    {
        $this->_setExtensionAttributes($extensionAttributes);
    }
}
