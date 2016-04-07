<?php
class Edge_Layout_Block_Adminhtml_Groups_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('layout_groupsGrid');
        $this->setDefaultSort('groups_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('layout/layout_groups')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id_group', array(
            'header'    => Mage::helper('layout')->__('ID'),
            'width'     => '50',
            'index'     => 'id_group'
        ));
         $this->addColumn('name', array(
            'header'    => Mage::helper('layout')->__('Name'),
            'index'     => 'name',
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
        $this->addColumn('action',
        array(
            'header' => Mage::helper('layout')->__('Action'),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                  'caption' => Mage::helper('layout')->__('View'),
                  'url' => array('base'=> '*/elements/index'),
                  'field' => 'id'
                )),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
        ));
        return parent::_prepareColumns();
    }
    
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('group_id' => $row->getId()));
    }
}