<?php
class Edge_Layout_Block_Adminhtml_Groups_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        /** @var $model Edge_Layout_Model_Layout */
        $model = Mage::registry('layout_group');
        $form = new Varien_Data_Form(array(
            'id'      => 'edit_form',
            'action'  => $this->getData('action'),
            'method'  => 'post',
            'enctype' => 'multipart/form-data'
        ));
        $form->setUseContainer(true);
        $form->setHtmlIdPrefix('layout_');
        $fieldset = $form->addFieldset('content_fieldset', array(
            'legend' => Mage::helper('cms')->__('Content'),
            'class'  => 'fieldset-wide'
        ));

        if ($model->getId()) {
            $fieldset->addField('id_group', 'hidden', array('name' => 'id_group'));
        }

        $fieldset->addField('name', 'text', array(
            'label' => Mage::helper('layout')->__('Name'),
            'name'  => 'name'
        ));
        $fieldset->addField('description', 'textarea', array(
            'label'  => Mage::helper('layout')->__('Description'),
            'name'   => 'description'
        ));

        $fieldset->addField('sort_order', 'text', array(
            'label' => Mage::helper('layout')->__('Sort Order'),
            'name'  => 'sort_order'
        ));

        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
}