<?php

namespace Niktar\OrderAutomation\Model\Rule\Email\Container;

use Magento\Sales\Model\Order\Email\Container\Container;
use Magento\Sales\Model\Order\Email\Container\IdentityInterface;

class RuleIdentity extends Container implements IdentityInterface
{
    /**
     * Configuration paths
     */
    public const XML_PATH_EMAIL_COPY_METHOD = 'order_automation/email/copy_method';
    public const XML_PATH_EMAIL_COPY_TO = 'order_automation/email/copy_to';
//    const XML_PATH_EMAIL_GUEST_TEMPLATE = 'sales_email/order_comment/guest_template';
//    const XML_PATH_EMAIL_TEMPLATE = 'sales_email/order_comment/template';
    public const XML_PATH_EMAIL_IDENTITY = 'order_automation/email/identity';
    public const XML_PATH_MODULE_ENABLED = 'order_automation/general/enabled';

    private $templateId = null;

    /**
     * Is email enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_MODULE_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->getStore()->getStoreId()
        );
    }

    /**
     * Return email copy_to list
     *
     * @return array|bool
     */
    public function getEmailCopyTo()
    {
        $data = $this->getConfigValue(self::XML_PATH_EMAIL_COPY_TO, $this->getStore()->getStoreId());
        if (!empty($data)) {
            return array_map('trim', explode(',', $data));
        }
        return false;
    }

    /**
     * Return email copy method
     *
     * @return mixed
     */
    public function getCopyMethod()
    {
        return $this->getConfigValue(self::XML_PATH_EMAIL_COPY_METHOD, $this->getStore()->getStoreId());
    }

    /**
     * Return guest template id
     *
     * @return mixed
     */
    public function getGuestTemplateId()
    {
        return $this->getTemplateId();
    }

    /**
     * Return template id
     *
     * @return mixed
     */
    public function getTemplateId()
    {
        return $this->templateId ?? 'order_automation_reminder_email_template';
    }

    /**
     * @param $templateId
     * @return $this
     */
    public function setTemplateId($templateId)
    {
        $this->templateId = $templateId;
        return $this;
    }

    /**
     * Return email identity
     *
     * @return mixed
     */
    public function getEmailIdentity()
    {
        return $this->getConfigValue(self::XML_PATH_EMAIL_IDENTITY, $this->getStore()->getStoreId());
    }
}
