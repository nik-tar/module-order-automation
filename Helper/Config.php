<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    public const XML_PATH_ENABLED = 'order_automation/general/enabled';
    public const XML_PATH_ORDER_LIMIT_PER_RUN = 'order_automation/general/order_limit_per_run';
    private const ORDER_LIMIT_PER_RUN_DEFAULT = 10;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        private ScopeConfigInterface $scopeConfig
    ) {
    }

    /**
     * @param string $scopeType
     * @param int|string|null $scopeCode
     * @return bool
     */
    public function isEnabled(
        string $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        int|string|null $scopeCode = null
    ): bool {
        return (bool)$this->scopeConfig->getValue(
            self::XML_PATH_ENABLED,
            $scopeType,
            $scopeCode
        );
    }

    /**
     * @param string $scopeType
     * @param int|string|null $scopeCode
     * @return int
     */
    public function getOrderLimitPerRun(
        string $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        int|string|null $scopeCode = null
    ): int {
        $orderLimit = (int)$this->scopeConfig->getValue(
            self::XML_PATH_ORDER_LIMIT_PER_RUN,
            $scopeType,
            $scopeCode
        );
        return $orderLimit > 0 ? $orderLimit : self::ORDER_LIMIT_PER_RUN_DEFAULT;
    }
}
