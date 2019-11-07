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
        $collection = $this->getCustomCollection();

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
        
        if ($dynamicField = $this->checkDynamicField()) {
            $this->addColumn(
                'value',
                [
                    'header' => ucfirst(str_replace("_", " ", $dynamicField)),
                    'index'  => 'value'
                ]
            );
        }

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
        return $this->getUrl('*/element/edit', ['element_id' => $row->getId(), 'group_id' => $row->getGroupId()]);
    }

    /**
     * Check if dynamic field exist
     */
    protected function checkDynamicField()
    {
        $collection = $this->getCustomCollection();
        
        $fieldCode = false;
        foreach ($collection as $element) {
            if (!is_null($element['show_in_grid'])) {                
                $fieldCode = $element['attribute_code'];
            }
        }

        return $fieldCode;
    }

    /**
     * Get Custom collection query with dynamic field
     */
    protected function getCustomCollection()
    {
        $collection = $this->elementFactory->create()->getCollection();
        $collection->getSelect()
            ->joinLeft(
                    ['leet' => 'layout_element_entity_text'],
                    'e.entity_id = leet.entity_id AND show_in_grid = leet.attribute_id',
                    ['leet.attribute_id', 'leet.value AS value'])
            ->joinLeft(
                ['eav' => 'eav_attribute'],
                'leet.attribute_id = eav.attribute_id',
                ['eav.attribute_code']
            )
            ->where('group_id = ?', $this->getRequest()->getParam('entity_id'));
        
        $secondInnerJoin = false;
        foreach ($collection->getData() as $element) {
            if ($element['value'] == NULL) {
                $secondInnerJoin = true;
                break;
            } 
        }

        if ($secondInnerJoin) {
            $collection = $this->elementFactory->create()->getCollection();
            $collection->getSelect()
                ->joinLeft(
                    ['leev' => 'layout_element_entity_varchar'],
                    'e.entity_id = leev.entity_id AND show_in_grid = leev.attribute_id',
                    ['leev.attribute_id', 'leev.value'])
                ->joinLeft(
                    ['eav' => 'eav_attribute'],
                    'leev.attribute_id = eav.attribute_id',
                    ['eav.attribute_code']
                )
                ->where('group_id = ?', $this->getRequest()->getParam('entity_id'));
        }

        return $collection;
    }
}
