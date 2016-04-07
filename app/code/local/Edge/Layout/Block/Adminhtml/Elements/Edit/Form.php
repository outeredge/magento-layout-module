<?php
class Edge_Layout_Block_Adminhtml_Elements_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        /** @var $model Edge_Layout_Model_Layout */
        $model = Mage::registry('layout_element');
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

        $fieldset->addField('fk_group', 'hidden', array('name' => 'fk_group'));

        if ($model->getId()) {
            $fieldset->addField('id_element', 'hidden', array('name' => 'id_element'));
        }

        $fieldset->addField('type', 'select', array(
            'label' => Mage::helper('layout')->__('Type'),
            'name'  => 'type',
            'required'  => true,
            'options'   => array(
                'top' => Mage::helper('cms')->__('Top'),
                'slideshow' => Mage::helper('cms')->__('Slideshow'),
                'banners' => Mage::helper('cms')->__('Banners'),
                'bottom_banners' => Mage::helper('cms')->__('Bottom Banners')
            ),
        ));
        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('layout')->__('Title'),
            'name'  => 'title'
        ));
        $fieldset->addField('description', 'textarea', array(
            'label'  => Mage::helper('layout')->__('Description'),
            'name'   => 'description'
        ));
        $fieldset->addField('link', 'text', array(
            'label' => Mage::helper('layout')->__('Link'),
            'name'  => 'link'
        ));
        $fieldset->addField('link_text', 'text', array(
            'label' => Mage::helper('layout')->__('Link Text'),
            'name'  => 'link_text'
        ));
        $fieldset->addField('image', 'image', array(
            'label' => Mage::helper('layout')->__('Image'),
            'name'  => 'image'
        ));

        switch ($model->getType()) {
            case 'slideshow':
            case 'banners':
            case 'bottom_banners':
                $fieldset->addField('overlay_style', 'select', array(
                    'label' => Mage::helper('layout')->__('Overlay Style'),
                    'name'  => 'overlay_style',
                    'options'   => array(
                        'full' => Mage::helper('cms')->__('Full'),
                        'circle' => Mage::helper('cms')->__('Circle')
                    ),
                ));
                $fieldset->addField('overlay_colour', 'text', array(
                    'label' => Mage::helper('layout')->__('Overlay Colour'),
                    'name'  => 'overlay_colour'
                ));
            break;
        }

        $fieldset->addField('sort_order', 'text', array(
            'label' => Mage::helper('layout')->__('Sort Order'),
            'name'  => 'sort_order'
        ));

        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
}