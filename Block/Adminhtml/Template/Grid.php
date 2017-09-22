<?php

namespace OuterEdge\Layout\Block\Adminhtml\Template;

use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Helper\Data as BackendHelper;
use OuterEdge\Layout\Model\TemplateFactory;
use Magento\Framework\DataObject;

class Grid extends Extended
{
    /**
     * @var TemplateFactory
     */
    private $templateFactory;

    /**
     * @param Context $context
     * @param BackendHelper $backendHelper
     * @param TemplateFactory $templateFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        BackendHelper $backendHelper,
        TemplateFactory $templateFactory,
        array $data = []
    ) {
        $this->templateFactory = $templateFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('templateGrid');
        $this->setDefaultSort('title');
        $this->setDefaultDir('ASC');
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->templateFactory->create()->getCollection();
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
            'code',
            [
                'header' => __('Template Code'),
                'index'  => 'code'
            ]
        );

        $this->addColumn(
            'sort_order',
            [
                'header' => __('Sort Order'),
                'index'  => 'sort_order'
            ]
        );

        $this->_eventManager->dispatch('template_grid_build', ['grid' => $this]);

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
        return $this->getUrl('*/*/edit', ['entity_id' => $row->getId()]);
    }
}
