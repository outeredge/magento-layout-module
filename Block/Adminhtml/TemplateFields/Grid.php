<?php

namespace OuterEdge\Layout\Block\Adminhtml\TemplateFields;

use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Helper\Data as BackendHelper;
use OuterEdge\Layout\Model\TemplateFieldsFactory;
use Magento\Framework\DataObject;

class Grid extends Extended
{
    /**
     * @var TemplateFieldsFactory
     */
    private $templateFieldsFactory;

    /**
     * @param Context $context
     * @param BackendHelper $backendHelper
     * @param TemplateFieldsFactory $templateFieldsFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        BackendHelper $backendHelper,
        TemplateFieldsFactory $templateFieldsFactory,
        array $data = []
    ) {
        $this->templateFieldsFactory = $templateFieldsFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('templateFieldsGrid');
        $this->setDefaultSort('title');
        $this->setDefaultDir('ASC');
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->templateFieldsFactory->create()->getCollection();
        $collection->addFieldToFilter('template_id', ['eq' => $this->getRequest()->getParam('entity_id')]);
        $this->setCollection($collection);
        
        return parent::_prepareCollection();
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'label',
            [
                'header' => __('Label'),
                'index'  => 'label'
            ]
        );

        $this->addColumn(
            'type',
            [
                'header' => __('Type'),
                'index'  => 'type'
            ]
        );

        $this->_eventManager->dispatch('templatefields_grid_build', ['grid' => $this]);

        return parent::_prepareColumns();
    }

     /**
     * Return url of given row
     *
     * @param DataObject $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/templateFields/edit', ['entity_id' => $row->getId(), 'template_id' => $row->getTemplateId()]);
    }

}
