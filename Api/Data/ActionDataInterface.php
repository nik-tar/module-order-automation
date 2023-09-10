<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Api\Data;

interface ActionDataInterface
{
    public const EMAIL_TEMPLATE = 'email_template';
    public const NEW_ORDER_STATUS = 'new_order_status';
    public const COMMENT_TEXT = 'comment_text';
    public const IS_COMMENT_VISIBLE_ON_FRONT = 'is_comment_visible_on_front';
    public const IS_CUSTOMER_NOTIFIED = 'is_customer_notified';

    /**
     * @return int|null
     */
    public function getEmailTemplate(): ?int;

    /**
     * @return string|null
     */
    public function getNewOrderStatus(): ?string;

    /**
     * @return string|null
     */
    public function getCommentText(): ?string;

    /**
     * @return bool|null
     */
    public function getIsCommentVisibleOnFront(): ?bool;

    /**
     * @return bool|null
     */
    public function getIsCustomerNotified(): ?bool;

    /**
     * @param int|null $emailTemplate
     * @return void
     */
    public function setEmailTemplate(?int $emailTemplate): void;

    /**
     * @param string|null $newOrderStatus
     * @return void
     */
    public function setNewOrderStatus(?string $newOrderStatus): void;

    /**
     * @param string|null $commentText
     * @return void
     */
    public function setCommentText(?string $commentText): void;

    /**
     * @param bool|null $isCommentVisibleOnFront
     * @return void
     */
    public function setIsCommentVisibleOnFront(?bool $isCommentVisibleOnFront): void;

    /**
     * @param bool|null $isCustomerNotified
     * @return void
     */
    public function setIsCustomerNotified(?bool $isCustomerNotified): void;

    /**
     * @return \Niktar\OrderAutomation\Api\Data\ActionDataExtensionInterface
     */
    public function getExtensionAttributes(): \Niktar\OrderAutomation\Api\Data\ActionDataExtensionInterface;

    /**
     * @param \Niktar\OrderAutomation\Api\Data\ActionDataExtensionInterface $extensionAttributes
     * @return void
     */
    public function setExtensionAttributes(\Niktar\OrderAutomation\Api\Data\ActionDataExtensionInterface $extensionAttributes): void;
}
