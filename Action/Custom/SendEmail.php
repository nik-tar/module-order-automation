<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Action\Custom;

use Niktar\OrderAutomation\Action\Action;
use Niktar\OrderAutomation\Api\ActionLogRepositoryInterface;
use Niktar\OrderAutomation\Api\Data\ActionLogInterfaceFactory;
use Niktar\OrderAutomation\Api\Data\RuleInterface;
use Niktar\OrderAutomation\Helper\Config;
use Niktar\OrderAutomation\Helper\Rule as RuleHelper;
use Niktar\OrderAutomation\Model\Rule\Email\Sender;
use Niktar\OrderAutomation\Registry\AfterAction;
use Psr\Log\LoggerInterface;

class SendEmail extends Action
{
    protected const ERROR_MESSAGE_BASE = 'There was an error while sending email by order automation rule. ';

    /**
     * @param Config $config
     * @param RuleHelper $ruleHelper
     * @param ActionLogInterfaceFactory $actionLogFactory
     * @param ActionLogRepositoryInterface $actionLogRepository
     * @param AfterAction $afterActionRegistry
     * @param LoggerInterface $logger
     * @param Sender $emailSender
     */
    public function __construct(
        Config $config,
        RuleHelper $ruleHelper,
        ActionLogInterfaceFactory $actionLogFactory,
        ActionLogRepositoryInterface $actionLogRepository,
        AfterAction $afterActionRegistry,
        LoggerInterface $logger,
        private readonly Sender $emailSender
    ) {
        parent::__construct($config, $ruleHelper, $actionLogFactory, $actionLogRepository, $afterActionRegistry, $logger);
    }

    /**
     * @inheritDoc
     */
    protected function executeAction(int $orderId, RuleInterface $rule): void
    {
        $this->emailSender->send($orderId, $rule);
    }
}
