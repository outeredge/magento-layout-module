<?php
class Edge_Layout_Block_Adminhtml_Elements_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('layout_groupsGrid');
        $this->setDefaultSort('elements_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('layout/layout_elements')->getCollection();
        if ($id = $this->getRequest()->getParam('id')) {
            $collection->addFilter('fk_group', $id);
        } else {
            $collection->addFilter('fk_group', '');
        }

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id_element', array(
            'header'    => Mage::helper('layout')->__('ID'),
            'width'     => '50',
            'index'     => 'id_element'
        ));
         $this->addColumn('type', array(
            'header'    => Mage::helper('layout')->__('Type'),
            'index'     => 'type',
        ));
        $this->addColumn('title', array(
            'header'    => Mage::helper('layout')->__('Title'),
            'index'     => 'title'
        ));
        $this->addColumn('description', array(
            'header'    => Mage::helper('layout')->__('Description'),
            'index'     => 'description'
        ));
        $this->addColumn('sort_order', array(
            'header'    => Mage::helper('layout')->__('Sort Order'),
            'width'     => '50',
            'index'     => 'sort_order'
        ));
        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array(
            'element_id' => $row->getId(),
            'fk_group' => $this->getRequest()->getParam('id'))
        );
    }
}