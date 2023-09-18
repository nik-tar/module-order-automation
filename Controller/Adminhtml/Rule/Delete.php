<?php

namespace Niktar\OrderAutomation\Controller\Adminhtml\Rule;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Niktar\OrderAutomation\Api\Data\RuleInterface as ModelInterface;
use Niktar\OrderAutomation\Api\RuleRepositoryInterface;

/**
 * Delete Rule controller.
 */
class Delete extends Action implements HttpPostActionInterface, HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session.
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Niktar_OrderAutomation::rules';

    /**
     * @param Context $context
     * @param RuleRepositoryInterface $ruleRepository
     */
    public function __construct(
        Context $context,
        private RuleRepositoryInterface $ruleRepository
    ) {
        parent::__construct($context);
    }

    /**
     * Delete Rule action.
     *
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var ResultInterface $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('*/*/');
        $ruleId = (int)$this->getRequest()->getParam(ModelInterface::RULE_ID);

        try {
            $this->ruleRepository->deleteById($ruleId);
            $this->messageManager->addSuccessMessage(__('You have successfully deleted Automation Rule'));
        } catch (CouldNotDeleteException|NoSuchEntityException|LocalizedException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }

        return $resultRedirect;
    }
}
