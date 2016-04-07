<?php
class Edge_Layout_Block_Adminhtml_Groups extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_groups';
        $this->_blockGroup = 'layout_groups';
        $this->_headerText = Mage::helper('layout')->__('Layout Groups');
        $this->_addButtonLabel = Mage::helper('layout')->__('Add Content');
        parent::__construct();
    }
}