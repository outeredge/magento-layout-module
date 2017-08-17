<?php

namespace OuterEdge\Layout\Block\Adminhtml\Element;

use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Helper\Data as BackendHelper;
use Magento\Framework\Module\Manager;
use OuterEdge\Layout\Model\ElementFactory;

class Grid extends Extended
{
    /**
     * @var Manager
     */
    protected $moduleManager;

    /**
     * @var ElementFactory
     */
    protected $_elementFactory;

    /**
     * @param Context $context
     * @param BackendHelper $backendHelper
     * @param Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Context $context,
        BackendHelper $backendHelper,
        ElementFactory $elementFactory,
        Manager $moduleManager,
        array $data = []
    ) {
        $this->_elementFactory = $elementFactory;
        $this->moduleManager = $moduleManager;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('elementGrid');
        $this->setDefaultSort('element_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('element_record');
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $id = $this->getRequest()->getParam('group_id');

        $collection = $this->_elementFactory->create()->getCollection()
            ->addFieldToFilter('group_id', $id);
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
            'element_id',
            [
                'header' => __('ID'),
                'type' => 'number',
                'index' => 'element_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );
        $this->addColumn(
            'title',
            [
                'header' => __('Title'),
                'index' => 'title',
                'class' => 'xxx'
            ]
        );
        $this->addColumn(
            'description',
            [
                'header' => __('Description'),
                'index' => 'description',
                'class' => 'xxx'
            ]
        );

        $this->addColumn(
            'edit_element',
            [
                'header' => __('Edit'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Edit'),
                        'url' => [
                            'base' => 'layout/element/edit'
                        ],
                        'field' => 'element_id'
                    ]
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action'
            ]
        );

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        return parent::_prepareColumns();
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('layout/*/index', ['_current' => true]);
    }

    /**
     * @param DataObject $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl(
            'layout/element/edit',
            ['element_id' => $row->getId()]
        );
    }
}
