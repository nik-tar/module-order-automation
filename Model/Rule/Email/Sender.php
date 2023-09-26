<?php

namespace Niktar\OrderAutomation\Model\Rule\Email;

use Magento\Framework\App\Area;
use Magento\Framework\DataObject;
use Magento\Framework\Event\ManagerInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Address\Renderer;
use Magento\Sales\Model\Order\Email\Container\Template;
use Magento\Sales\Model\Order\Email\NotifySender;
use Magento\Sales\Model\Order\Email\SenderBuilderFactory;
use Magento\Store\Model\App\Emulation;
use Niktar\OrderAutomation\Api\Data\RuleInterface;
use Niktar\OrderAutomation\Model\Rule\Email\Container\RuleIdentity;
use Psr\Log\LoggerInterface;

class Sender extends NotifySender
{
    /**
     * @var Renderer
     */
    protected $addressRenderer;

    /**
     * @param Template $templateContainer
     * @param RuleIdentity $identityContainer
     * @param SenderBuilderFactory $senderBuilderFactory
     * @param LoggerInterface $logger
     * @param Renderer $addressRenderer
     * @param ManagerInterface $eventManager
     * @param Emulation $appEmulation
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        Template $templateContainer,
        RuleIdentity $identityContainer,
        SenderBuilderFactory $senderBuilderFactory,
        LoggerInterface $logger,
        Renderer $addressRenderer,
        protected readonly ManagerInterface $eventManager,
        private readonly Emulation $appEmulation,
        private readonly OrderRepositoryInterface $orderRepository
    ) {
        parent::__construct($templateContainer, $identityContainer, $senderBuilderFactory, $logger, $addressRenderer);
        $this->addressRenderer = $addressRenderer;
    }

    /**
     * Send email to customer
     *
     * @param int $orderId
     * @param RuleInterface $rule
     * @return bool
     */
    public function send(int $orderId, RuleInterface $rule)
    {
        /** @var Order $order */
        $order = $this->orderRepository->get($orderId);
        $this->identityContainer->setStore($order->getStore());
        $this->identityContainer->setTemplateId($rule->getActionData()->getEmailTemplate());

        $this->appEmulation->startEnvironmentEmulation($order->getStoreId(), Area::AREA_FRONTEND, true);
        $transport = [
            'order' => $order,
            'store' => $order->getStore(),
            'order_data' => [
                'customer_name' => $order->getCustomerName(),
                'frontend_status_label' => $order->getFrontendStatusLabel()
            ]
        ];
        $transportObject = new DataObject($transport);
        $this->appEmulation->stopEnvironmentEmulation();

        /**
         * Event argument `transport` is @deprecated. Use `transportObject` instead.
         */
        $this->eventManager->dispatch(
            'email_order_automation_set_template_vars_before',
            [
                'sender' => $this,
                'transport' => $transportObject->getData(),
                'transportObject' => $transportObject,
                'rule' => $rule
            ]
        );

        $this->templateContainer->setTemplateVars($transportObject->getData());

        return $this->checkAndSend($order);
    }
}
