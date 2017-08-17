<?php

namespace OuterEdge\Layout\Block\Adminhtml\Element;

use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Helper\Data as BackendHelper;
use OuterEdge\Layout\Model\ElementFactory;
use Magento\Framework\DataObject;

class Grid extends Extended
{
    /**
     * @var ElementFactory
     */
    private $elementFactory;

    /**
     * @param Context $context
     * @param BackendHelper $backendHelper
     * @param ElementFactory $elementFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        BackendHelper $backendHelper,
        ElementFactory $elementFactory,
        array $data = []
    ) {
        $this->elementFactory = $elementFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('elementGrid');
        $this->setDefaultSort('title');
        $this->setDefaultDir('ASC');
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->elementFactory->create()->getCollection();
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
            'title',
            [
                'header' => __('Title'),
                'index'  => 'title'
            ]
        );

        $this->addColumn(
            'sort_order',
            [
                'header' => __('Sort Order'),
                'index'  => 'sort_order'
            ]
        );

        $this->_eventManager->dispatch('element_grid_build', ['grid' => $this]);

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
        return $this->getUrl('*/element/edit', ['element_id' => $row->getElementId()]);
    }
}
