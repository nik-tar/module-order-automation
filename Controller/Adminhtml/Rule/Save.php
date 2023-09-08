<?php

namespace Niktar\OrderAutomation\Controller\Adminhtml\Rule;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\CouldNotSaveException;
use Niktar\OrderAutomation\Api\Data\OrderAutomationRuleInterface as ModelInterface;
use Niktar\OrderAutomation\Api\Data\OrderAutomationRuleInterfaceFactory as ModelFactory;
use Niktar\OrderAutomation\Api\OrderAutomationRuleRepositoryInterface as RuleRepository;
use Niktar\Rule\Api\Data\RuleInterface;
use Niktar\Rule\Api\Data\RuleInterfaceFactory;

/**
 * Save Rule controller action.
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Niktar_OrderAutomation::rules';

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param ModelFactory $ruleFactory
     * @param RuleRepository $ruleRepository
     */
    public function __construct(
        Context $context,
        private DataPersistorInterface $dataPersistor,
        private ModelFactory $ruleFactory,
        private RuleRepository $ruleRepository
    ) {
        parent::__construct($context);
    }

    /**
     * Save Rule Action.
     *
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $params = $this->getRequest()->getParams();

        try {
            /** @var ModelInterface|DataObject $ruleModel */
            $ruleModel = $this->ruleFactory->create();
            $ruleModel->addData($params['general']);
            $this->ruleRepository->save($ruleModel);
            $this->messageManager->addSuccessMessage(
                __('The Automation Rule data was saved successfully')
            );
            $this->dataPersistor->clear('entity');
        } catch (CouldNotSaveException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
            $this->dataPersistor->set('entity', $params);

            return $resultRedirect->setPath('*/*/edit', [
                ModelInterface::RULE_ID => $this->getRequest()->getParam(ModelInterface::RULE_ID)
            ]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}
