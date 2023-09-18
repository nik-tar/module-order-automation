<?php

namespace Niktar\OrderAutomation\Controller\Adminhtml\Rule;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Niktar\OrderAutomation\Api\Data\ActionDataInterface;
use Niktar\OrderAutomation\Api\Data\RuleInterface as ModelInterface;
use Niktar\OrderAutomation\Api\Data\RuleInterfaceFactory as ModelFactory;
use Niktar\OrderAutomation\Api\RuleRepositoryInterface as RuleRepository;

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
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        Context $context,
        private DataPersistorInterface $dataPersistor,
        private ModelFactory $ruleFactory,
        private RuleRepository $ruleRepository,
        private DataObjectHelper $dataObjectHelper
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
        /** @var Http $request */
        $request = $this->getRequest();
        $data = $request->getPostValue();
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if (empty($data)) {
            return $resultRedirect->setPath('*/*/');
        }

        foreach (ActionDataInterface::BOOLEAN_FIELDS as $field) {
            if (isset($data[$field]) && $data[$field] === 'true') {
                $data[$field] = true;
            } else {
                $data[$field] = false;
            }
        }

        if (empty($data[ModelInterface::RULE_ID])) {
            $data[ModelInterface::RULE_ID] = null;
        }
        $ruleModel = $this->ruleFactory->create();

        $ruleId = $request->getParam('rule_id');
        if ($ruleId) {
            try {
                $ruleModel = $this->ruleRepository->getById($ruleId);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage(__('This rule no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
        }

        $data[ModelInterface::ACTION_DATA] = [
            ActionDataInterface::ACTION_TYPE => $data[ActionDataInterface::ACTION_TYPE] ?? 0,
            ActionDataInterface::EMAIL_TEMPLATE => $data[ActionDataInterface::EMAIL_TEMPLATE] ?? null,
            ActionDataInterface::NEW_ORDER_STATUS => $data[ActionDataInterface::NEW_ORDER_STATUS] ?? null,
            ActionDataInterface::COMMENT_TEXT => $data[ActionDataInterface::COMMENT_TEXT] ?? null,
            ActionDataInterface::IS_COMMENT_VISIBLE_ON_FRONT => $data[ActionDataInterface::IS_COMMENT_VISIBLE_ON_FRONT] ?? false,
            ActionDataInterface::IS_CUSTOMER_NOTIFIED => $data[ActionDataInterface::IS_CUSTOMER_NOTIFIED] ?? false
        ];

        $this->dataObjectHelper->populateWithArray(
            $ruleModel,
            $data,
            ModelInterface::class
        );

        try {
            $this->ruleRepository->save($ruleModel);
            $this->messageManager->addSuccessMessage(
                __('The Automation Rule data was saved successfully')
            );
            return $this->processResultRedirect($ruleModel, $resultRedirect);
        } catch (LocalizedException $e) {
            $this->messageManager->addExceptionMessage($e->getPrevious() ?: $e);
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the rule.'));
        }

        $this->dataPersistor->set('niktar_order_automation_rule_form', $data);
        return $resultRedirect->setRefererOrBaseUrl();
    }

    /**
     * @param ModelInterface $model
     * @param Redirect $resultRedirect
     * @return Redirect
     */
    private function processResultRedirect(ModelInterface $model, Redirect $resultRedirect): Redirect
    {
        $this->dataPersistor->clear('niktar_order_automation_rule_form');
        if ($this->getRequest()->getParam('back')) {
            return $resultRedirect->setPath('*/*/edit', ['rule_id' => $model->getRuleId(), '_current' => true]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
