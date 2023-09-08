<?php

namespace Niktar\OrderAutomation\Controller\Adminhtml\Rule;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * Rule backend index (list) controller.
 */
class Index extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session.
     */
    public const ADMIN_RESOURCE = 'Niktar_OrderAutomation::rules';

    /**
     * Execute action based on request and return result.
     *
     * @return ResultInterface|ResponseInterface
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $resultPage->setActiveMenu('Niktar_OrderAutomation::rules');
        $resultPage->addBreadcrumb(__('Automation Rule'), __('Automation Rule'));
        $resultPage->addBreadcrumb(__('Manage Automation Rules'), __('Manage Automation Rules'));
        $resultPage->getConfig()->getTitle()->prepend(__('Automation Rule List'));

        return $resultPage;
    }
}
