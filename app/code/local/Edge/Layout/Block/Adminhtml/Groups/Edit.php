<?php
class Edge_Layout_Block_Adminhtml_Groups_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Initialize elements edit block
     * @return void
     */
    public function __construct()
    {
        $this->_objectId   = 'group_id';
        $this->_controller = 'adminhtml_groups';
        $this->_blockGroup = 'layout_groups';
        parent::__construct();
        $this->_updateButton('save', 'label', Mage::helper('layout')->__('Save Item'));
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save and Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);
        $this->_updateButton('delete', 'label', Mage::helper('layout')->__('Delete Item'));
        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * Retrieve text for header element depending on loaded page
     * @return string
     */
    public function getHeaderText()
    {
        if (Mage::registry('layout_group')->getId()) {
            return Mage::helper('layout')->__("Edit Item '%s'", $this->escapeHtml(Mage::registry('layout_group')->getName()));
        }
        else {
            return Mage::helper('layout')->__('New Item');
        }
    }
    
    /**
     * Get form action URL
     * @return string
     */
    public function getFormActionUrl()
    {
        if ($this->hasFormActionUrl()) {
            return $this->getData('form_action_url');
        }
        return $this->getUrl('*/*/save');
    }
}