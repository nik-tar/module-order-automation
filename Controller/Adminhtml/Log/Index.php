<?php

namespace Niktar\OrderAutomation\Controller\Adminhtml\Log;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

class Index extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session.
     */
    public const ADMIN_RESOURCE = 'Niktar_OrderAutomation::log';

    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $resultPage->setActiveMenu('Niktar_OrderAutomation::log');
        $resultPage->addBreadcrumb(__('Automation Action Log'), __('Automation Action Log'));
        $resultPage->addBreadcrumb(__('Automation Action Log'), __('Automation Action Log'));
        $resultPage->getConfig()->getTitle()->prepend(__('Automation Action Log'));

        return $resultPage;
    }
}
