<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Model\ResourceModel\ActionLog;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Niktar\OrderAutomation\Model\ActionLog as OrderAutomationActionLogModel;
use Niktar\OrderAutomation\Model\ResourceModel\ActionLog as OrderAutomationActionLogResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'order_automation_action_log_collection';

    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init(
            OrderAutomationActionLogModel::class,
            OrderAutomationActionLogResourceModel::class
        );
    }
}
