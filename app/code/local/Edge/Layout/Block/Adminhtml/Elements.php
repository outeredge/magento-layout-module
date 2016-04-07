<?php
class Edge_Layout_Block_Adminhtml_Elements extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $fk_group = $this->getRequest()->getParam('id');

        $this->_controller = 'adminhtml_elements';
        $this->_blockGroup = 'layout_groups';
        $this->_headerText = Mage::helper('layout')->__('Layout');
        $this->_removeButton('add');

        $dataAdd = array(
        'label' =>  Mage::helper('layout')->__('Add Content'),
        'onclick'   => 'setLocation(\'' . $this->getUrl('*/elements/new/fk_group/'.$fk_group) . '\')',
        'class'     =>  'save'
        );
        $this->addButton ('add_content', $dataAdd, 0, 2,  'header');

        $data = array(
        'label' =>  Mage::helper('layout')->__('Back'),
        'onclick'   => 'setLocation(\'' . $this->getUrl('*/groups/index/') . '\')',
        'class'     =>  'back'
        );
        $this->addButton ('my_back', $data, 0, 1,  'header');

        parent::__construct();
        $this->_removeButton('add');
    }
}