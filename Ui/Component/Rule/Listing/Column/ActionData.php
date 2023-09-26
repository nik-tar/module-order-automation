<?php

namespace Niktar\OrderAutomation\Ui\Component\Rule\Listing\Column;

use InvalidArgumentException;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Niktar\OrderAutomation\Helper\ActionData as ActionDataHelper;

class ActionData extends Column
{
    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param SerializerInterface $serializer
     * @param ActionDataHelper $actionDataHelper
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        private SerializerInterface $serializer,
        private ActionDataHelper $actionDataHelper,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @inheritDoc
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $colName = $this->getData('name');

                if (!isset($item[$colName])) {
                    continue;
                }

                try {
                    $data = is_array($item[$colName])
                        ? $item[$colName]
                        : $this->serializer->unserialize($item[$colName]);
                } catch (InvalidArgumentException $exception) {
                    $item[$colName] = [];
                    continue;
                }
                $frontendData = $this->actionDataHelper->getFrontendRepresentation($data);
                $data['frontend_representation'] = $frontendData;

                $item[$colName] = $data;
            }
        }

        return $dataSource;
    }

}
