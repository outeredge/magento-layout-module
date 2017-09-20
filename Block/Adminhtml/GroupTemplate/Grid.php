<?php

namespace OuterEdge\Layout\Block\Adminhtml\GroupTemplate;

use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Helper\Data as BackendHelper;
use OuterEdge\Layout\Model\GroupTemplateFactory;
use Magento\Framework\DataObject;

class Grid extends Extended
{
    /**
     * @var GroupTemplateFactory
     */
    private $groupTemplateFactory;

    /**
     * @param Context $context
     * @param BackendHelper $backendHelper
     * @param GroupTemplateFactory $groupTemplateFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        BackendHelper $backendHelper,
        GroupTemplateFactory $groupTemplateFactory,
        array $data = []
    ) {
        $this->groupTemplateFactory = $groupTemplateFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('groupTemplateGrid');
        $this->setDefaultSort('title');
        $this->setDefaultDir('ASC');
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->groupTemplateFactory->create()->getCollection();
        $collection->addFieldToFilter('group_id', ['eq' => $this->getRequest()->getParam('group_id')]);
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

        $this->addColumn(
            'sort_order',
            [
                'header' => __('Sort Order'),
                'index'  => 'sort_order'
            ]
        );

        $this->_eventManager->dispatch('groupTemplate_grid_build', ['grid' => $this]);

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
        return $this->getUrl('*/groupTemplate/edit', ['template_id' => $row->getId()]);
    }
}
