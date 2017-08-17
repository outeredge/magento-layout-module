<?php

namespace OuterEdge\Layout\Block\Adminhtml\Element\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;

class Main extends Generic
{
    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        $group = $this->_coreRegistry->registry('group');

        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Group Properties')]);

        if ($group->getId()) {
            $fieldset->addField('group_id', 'hidden', ['name' => 'group_id']);
        }

        $fieldset->addField(
            'group_code',
            'text',
            [
                'name'     => 'group[group_code]',
                'label'    => __('Code'),
                'title'    => __('Code'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'title',
            'text',
            [
                'name'  => 'group[title]',
                'label' => __('Title'),
                'title' => __('Title')
            ]
        );
        $fieldset->addField(
            'description',
            'textarea',
            [
                'name'  => 'group[description]',
                'label' => __('Description'),
                'title' => __('Description')
            ]
        );
        $fieldset->addField(
            'sort_order',
            'text',
            [
                'name'  => 'group[sort_order]',
                'label' => __('Sort Order'),
                'title' => __('Sort Order')
            ]
        );

        $form->setValues($group->getData());
        $this->setForm($form);

        $this->_eventManager->dispatch('group_form_build_main_tab', ['form' => $form]);

        return parent::_prepareForm();
    }
}


    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
//    protected function _prepareForm()
//    {
//        $idGroup = $this->getRequest()->getParam('group_id');
//
//        $model = $this->_coreRegistry->registry('layout_element_form_data');
//
//        /** @var \Magento\Framework\Data\Form $form */
//        $form = $this->_formFactory->create();
//
//        $form->setHtmlIdPrefix('page_');
//
//        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Element Information')]);
//
//        $fieldset->addType('image', 'OuterEdge\Layout\Block\Adminhtml\Element\Helper\Image');
//
//        if ($model->getId()) {
//            $fieldset->addField('element_id', 'hidden', ['name' => 'element_id']);
//            $fieldset->addField('group_id', 'hidden', ['name' => 'group_id']);
//        }
//
//        if ($idGroup) {
//            $model->setGroupId($idGroup);
//            $fieldset->addField('group_id', 'hidden', ['name' => 'group_id']);
//        }
//
//        $fieldset->addField(
//            'title',
//            'text',
//            [
//                'name' => 'title',
//                'label' => __('Title'),
//                'title' => __('Title'),
//                'required' => true
//            ]
//        );
//        $fieldset->addField(
//            'description',
//            'textarea',
//            [
//                'name' => 'description',
//                'label' => __('Description'),
//                'title' => __('Description'),
//                'required' => true
//            ]
//        );
//        $fieldset->addField(
//            'link',
//            'text',
//            [
//                'name' => 'link',
//                'label' => __('Link'),
//                'title' => __('Link'),
//                'required' => false
//            ]
//        );
//        $fieldset->addField(
//            'link_text',
//            'text',
//            [
//                'name' => 'link_text',
//                'label' => __('Link Text'),
//                'title' => __('Link Text'),
//                'required' => false
//            ]
//        );
//        $fieldset->addField(
//            'image',
//            'image',
//            [
//                'name' => 'image',
//                'label' => __('Image'),
//                'title' => __('Image'),
//                'required' => false,
//                'note' => 'Allow image type: jpg, jpeg, gif, png',
//            ]
//        );
//        $fieldset->addField(
//            'sort_order',
//            'text',
//            [
//                'name' => 'sort_order',
//                'label' => __('Sort Order'),
//                'title' => __('Sort Order'),
//                'required' => false
//            ]
//        );
//
//        $form->setValues($model->getData());
//        $this->setForm($form);
//
//        return parent::_prepareForm();
//    }