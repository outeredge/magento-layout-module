<?php

namespace OuterEdge\Layout\Block\Adminhtml\TemplateFields\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config;
use OuterEdge\Layout\Helper\Data as Helper;

class Main extends Generic
{
    /**
     * @var Helper
     */
    protected $helper;
    
    /**
     * @var Config
     */
    protected $_wysiwygConfig;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Config $wysiwygConfig
     * @param Helper $helper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Config $wysiwygConfig,
        Helper $helper,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->helper = $helper;
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
        $templateFields = $this->_coreRegistry->registry('templateFieldsModel');
        $templateId = $this->getRequest()->getParam('template_id');

        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Template Fields Properties')]);

        if ($templateFields->getId()) {
            $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
            $fieldset->addField('template_id', 'hidden', ['name' => 'template_id']);
            $fieldset->addField('eav_attribute_id', 'hidden', ['name' => 'eav_attribute_id']);
        } elseif ($templateId) {
            $templateFields->setTemplateId($templateId);
            $fieldset->addField('template_id', 'hidden', ['name' => 'template_id']);
            $templateCode = $this->helper->getTemplate($templateId)->getCode();
            $templateFields->setTemplateCode($templateCode);
            $fieldset->addField('template_code', 'hidden', ['name' => 'template_code']);
        }
       
        if ($templateFields->getEavAttributeId()) {
            $fieldset->addField(
                'attribute_code',
                'text',
                [
                    'name'  => 'attribute_code',
                    'label' => __('Field Code'),
                    'title' => __('Field Code'),
                    'readonly' => true
                ]
            );
        } else {
            $fieldset->addField(
                'attribute_code',
                'text',
                [
                    'name'  => 'attribute_code',
                    'label' => __('Field Code'),
                    'title' => __('Field Code')
                ]
            );
        }
        
        $fieldset->addField(
            'frontend_label',
            'text',
            [
                'name'  => 'frontend_label',
                'label' => __('Label'),
                'title' => __('Label'),
                'required' => true
            ]
        );
        
        $fieldset->addField(
            'frontend_input',
            'select',
            [
                'name'  => 'frontend_input',
                'label' => __('Frontend Type'),
                'title' => __('Frontend Type'),
                'options' => [
                    'text'          => __('Text Field'),
                    'textarea'      => __('Text Area'),
                    'date'          => __('Date'),
                    'boolean'       => __('Yes/No'),
                    'multiselect'   => __('Multiple Select'),
                    'select'        => __('Dropdown'),
                    'editor'        => __('Editor'),
                    'image'         => __('Image'),
                    'position'      => __('Position')
                ]
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
        
        $form->setValues($templateFields->getData());
        $this->setForm($form);

        $this->_eventManager->dispatch('templatefields_form_build_main_tab', ['form' => $form]);

        return parent::_prepareForm();
    }
}
