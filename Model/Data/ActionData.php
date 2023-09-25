<?php

namespace Niktar\OrderAutomation\Model\Data;

use Magento\Framework\Api\AbstractExtensibleObject;
use Niktar\OrderAutomation\Api\Data\ActionDataInterface;

class ActionData extends AbstractExtensibleObject implements ActionDataInterface
{
    /**
     * @inheritDoc
     */
    public function getActionType(): string
    {
        return $this->_get(self::ACTION_TYPE);
    }

    /**
     * @inheritDoc
     */
    public function getEmailTemplate(): string|int|null
    {
        return $this->_get(self::EMAIL_TEMPLATE);
    }

    /**
     * @inheritDoc
     */
    public function getNewOrderStatus(): ?string
    {
        return $this->_get(self::NEW_ORDER_STATUS);
    }

    /**
     * @inheritDoc
     */
    public function getCommentText(): ?string
    {
        return $this->_get(self::COMMENT_TEXT);
    }

    /**
     * @inheritDoc
     */
    public function getIsCommentVisibleOnFront(): ?bool
    {
        return $this->_get(self::IS_COMMENT_VISIBLE_ON_FRONT);
    }

    /**
     * @inheritDoc
     */
    public function getIsCustomerNotified(): ?bool
    {
        return $this->_get(self::IS_CUSTOMER_NOTIFIED);
    }

    /**
     * @inheritDoc
     */
    public function setActionType(string $actionType): void
    {
        $this->setData(self::ACTION_TYPE, $actionType);
    }

    /**
     * @inheritDoc
     */
    public function setEmailTemplate(string|int|null $emailTemplate): void
    {
        $this->setData(self::EMAIL_TEMPLATE, $emailTemplate);
    }

    /**
     * @inheritDoc
     */
    public function setNewOrderStatus(?string $newOrderStatus): void
    {
        $this->setData(self::NEW_ORDER_STATUS, $newOrderStatus);
    }

    /**
     * @inheritDoc
     */
    public function setCommentText(?string $commentText): void
    {
        $this->setData(self::COMMENT_TEXT, $commentText);
    }

    /**
     * @inheritDoc
     */
    public function setIsCommentVisibleOnFront(?bool $isCommentVisibleOnFront): void
    {
        $this->setData(self::IS_COMMENT_VISIBLE_ON_FRONT, $isCommentVisibleOnFront);
    }

    /**
     * @inheritDoc
     */
    public function setIsCustomerNotified(?bool $isCustomerNotified): void
    {
        $this->setData(self::IS_CUSTOMER_NOTIFIED, $isCustomerNotified);
    }

    /**
     * @inheritDoc
     */
    public function getExtensionAttributes(): \Niktar\OrderAutomation\Api\Data\ActionDataExtensionInterface
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * @inheritDoc
     */
    public function setExtensionAttributes(\Niktar\OrderAutomation\Api\Data\ActionDataExtensionInterface $extensionAttributes): void
    {
        $this->_setExtensionAttributes($extensionAttributes);
    }
}
