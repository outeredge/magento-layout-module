<?php

namespace OuterEdge\Layout\Block\Adminhtml\Group\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config;
use OuterEdge\Layout\Helper\Data as TemplatesHelper;
use Magento\Store\Model\System\Store;

class Main extends Generic
{
    /**
     * @var TemplatesHelper
     */
    protected $_templates;
    
    /**
     * @var Config
     */
    protected $_wysiwygConfig;

    /**
     * @var Store
     */
    protected $_systemStore;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Config $wysiwygConfig
     * @param TemplatesHelper $templates
     * @param Store $systemStore
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Config $wysiwygConfig,
        TemplatesHelper $templates,
        Store $systemStore,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_templates = $templates;
        $this->_systemStore = $systemStore;
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

        if ($group->getId()) {
            $elementCollection = $this->_templates->getElementCollection()
                ->addFieldToFilter('group_id', ['eq' => $group->getId()]);

            if (!empty($element = $elementCollection->getData())) {
                $showInGrid = reset($element);
                $group->setShowInGrid($showInGrid['show_in_grid']);
            }
        }

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
                    'add_variables' => false,
                    'add_widgets'   => false,
                    'add_images'    => false
                ])
            ]
        );

        $fieldset->addField(
            'template_file',
            'text',
            [
                'name'  => 'group[template_file]',
                'label' => __('Template Widget File'),
                'title' => __('Template Widget File')
            ]
        );

        if ($group->getId()) {
            $fieldset->addField(
                'show_in_grid',
                'select',
                [
                    'name'  => 'show_in_grid',
                    'label' => __('Show in grid'),
                    'title' => __('Show in grid'),
                    'values' => $this->getAllFieldsOptions($group->getTemplateId()),
                    'note' => __('Select extra text field to show in grid')
                ]
            );
        }

        $fieldset->addField(
            'store_ids',
            'multiselect',
            [
                'name'  => 'group[store_ids]',
                'label'    => __('Store Views'),
                'title'    => __('Store Views'),
                'required' => true,
                'values'   => $this->_systemStore->getStoreValuesForForm(false, true),
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

     /**
     * options array with all template fields
     */
    protected function getAllFieldsOptions($id)
    {
        $allFieldsCollection = $this->_templates->getTemplateFieldsCollection()
        ->addFieldToFilter('template_id', ['eq' => $id]);
        $allFieldsCollection->getSelect()
            ->join(
                ['eav' => 'eav_attribute'],
                'main_table.eav_attribute_id = eav.attribute_id',
                ['eav.attribute_code', 'eav.frontend_label', 'eav.frontend_input']
            );
        $allFieldsCollection->setOrder('sort_order', 'ASC');

        $allOptions = [];
        $allOptions['NULL'] = '  ';
        foreach ($allFieldsCollection as $element) {
            if ($element['frontend_input'] == 'text') {
                $allOptions[$element['eav_attribute_id']] = $element['attribute_code'];
            }
        }

        return $allOptions;
    }
}
