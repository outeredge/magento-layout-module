<?php

namespace OuterEdge\Layout\Block\Adminhtml\Group;

use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Helper\Data as BackendHelper;
use OuterEdge\Layout\Model\GroupFactory;
use Magento\Framework\DataObject;

class Grid extends Extended
{
    /**
     * @var GroupFactory
     */
    private $groupFactory;

    /**
     * @param Context $context
     * @param BackendHelper $backendHelper
     * @param GroupFactory $groupFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        BackendHelper $backendHelper,
        GroupFactory $groupFactory,
        array $data = []
    ) {
        $this->groupFactory = $groupFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('groupGrid');
        $this->setDefaultSort('title');
        $this->setDefaultDir('ASC');
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->groupFactory->create()->getCollection();
        $collection->getSelect()->join('layout_template as ly', 'main_table.template_id = ly.entity_id', 'ly.code as template_name');

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
            'group_code',
            [
                'header' => __('Code'),
                'index'  => 'group_code'
            ]
        );

        $this->addColumn(
            'sort_order',
            [
                'header' => __('Sort Order'),
                'index'  => 'sort_order'
            ]
        );

        $this->_eventManager->dispatch('group_grid_build', ['grid' => $this]);

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
