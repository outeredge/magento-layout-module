<?php

namespace OuterEdge\Layout\Block\Adminhtml\Element\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config;

class Main extends Generic
{
    /**
     * @var Config
     */
    protected $_wysiwygConfig;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Config $wysiwygConfig
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Config $wysiwygConfig,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        $element = $this->_coreRegistry->registry('element');
        $groupId = $this->getRequest()->getParam('group_id');

        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'enctype' => 'multipart/form-data', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Element Properties')]);
        $fieldset->addType('image', 'OuterEdge\Layout\Block\Adminhtml\Element\Helper\Image');

        if ($element->getId()) {
            $fieldset->addField('element_id', 'hidden', ['name' => 'element_id']);
            $fieldset->addField('group_id', 'hidden', ['name' => 'group_id']);
        } elseif ($groupId) {
            $element->setGroupId($groupId);
            $fieldset->addField('group_id', 'hidden', ['name' => 'group_id']);
        }

        $fieldset->addField(
            'title',
            'text',
            [
                'name'     => 'title',
                'label'    => __('Title'),
                'title'    => __('Title')
            ]
        );

        $fieldset->addField(
            'description',
            'editor',
            [
                'name'    => 'description',
                'label'   => __('Description'),
                'title'   => __('Description'),
                'wysiwyg' => true,
                'config'  => $this->_wysiwygConfig->getConfig([
                    'hidden'        => $element->getDescription() === strip_tags($element->getDescription()),
                    'add_variables' => false,
                    'add_widgets'   => false,
                    'add_images'    => false
                ])
            ]
        );

        $fieldset->addField(
            'link',
            'text',
            [
                'name'  => 'link',
                'label' => __('Link'),
                'title' => __('Link')
            ]
        );

        $fieldset->addField(
            'link_text',
            'text',
            [
                'name'  => 'link_text',
                'label' => __('Text for link'),
                'title' => __('Text for link')
            ]
        );

        $fieldset->addField(
            'image',
            'image',
            [
                'name'  => 'image',
                'label' => __('Image'),
                'title' => __('Image'),
                'note'  => 'Allowed types: jpg, jpeg, gif, png, svg'
            ]
        );

        $fieldset->addField(
            'sort_order',
            'text',
            [
                'name'  => 'sort_order',
                'label' => __('Sort Order'),
                'title' => __('Sort Order')
            ]
        );

        $form->setValues($element->getData());
        $this->setForm($form);

        $this->_eventManager->dispatch('element_form_build_main_tab', ['form' => $form]);

        return parent::_prepareForm();
    }
}
