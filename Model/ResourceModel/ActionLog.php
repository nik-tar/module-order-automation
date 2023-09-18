<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Niktar\OrderAutomation\Api\Data\ActionLogInterface;

class ActionLog extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'order_automation_action_log_resource_model';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            'order_automation_action_log',
            ActionLogInterface::ACTION_ID
        );
    }
}
