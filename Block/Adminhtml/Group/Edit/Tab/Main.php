<?php

namespace OuterEdge\Layout\Block\Adminhtml\Group\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config;
use OuterEdge\Layout\Helper\Data as TemplatesHelper;

class Main extends Generic
{
    protected $_templates;
    
    /**
     * @var Config
     */
    protected $_wysiwygConfig;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Config $wysiwygConfig
     * @param TemplatesHelper $templates
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Config $wysiwygConfig,
        TemplatesHelper $templates,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_templates = $templates;
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
        $group = $this->_coreRegistry->registry('groupModel');

        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Group Properties')]);

        if ($group->getId()) {
            $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
            $fieldset->addField(
                'template_id',
                'select',
                [
                    'name'     => 'group[template_id]',
                    'label'    => __('Template Name'),
                    'title'    => __('Template Name'),
                    'disabled' => true,
                    'required' => true,
                    'values' => $this->_templates->getTemplates(),
                    'note'  => 'This code represent the template where field\'s are defined'
                ]
            );
            
            $fieldset->addField(
                'group_code',
                'text',
                [
                    'name'     => 'group[group_code]',
                    'label'    => __('Code'),
                    'title'    => __('code'),
                    'readonly' => true,
                    'required' => true,
                    'note'  => 'This code represent the group name'
                ]
            );
        } else {
             $fieldset->addField(
                 'template_id',
                 'select',
                 [
                    'name'     => 'group[template_id]',
                    'label'    => __('Template Name'),
                    'title'    => __('Template Name'),
                    'options' => $this->_templates->getTemplates(),
                    'required' => true,
                    'note'  => 'This code represent the template where field\'s are defined'
                 ]
             );
            
            $fieldset->addField(
                'group_code',
                'text',
                [
                    'name'     => 'group[group_code]',
                    'label'    => __('Code'),
                    'title'    => __('Code'),
                    'required' => true,
                    'note'  => 'This code represent the group name. Can\'t be changed.'
                ]
            );
        }

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
            'editor',
            [
                'name'    => 'group[description]',
                'label'   => __('Description'),
                'title'   => __('Description'),
                'wysiwyg' => true,
                'config'  => $this->_wysiwygConfig->getConfig([
                    'hidden'        => $group->getDescription() === strip_tags($group->getDescription()),
                    'add_variables' => false,
                    'add_widgets'   => false,
                    'add_images'    => false
                ])
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
