<?php

namespace OuterEdge\Layout\Block\Adminhtml\Element\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config;
use OuterEdge\Layout\Helper\Templates\Factory as TemplatesHelper;

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
        $element = $this->_coreRegistry->registry('elementModel');
        $groupCode = $this->_coreRegistry->registry('groupCode');     
        $groupId = $this->getRequest()->getParam('group_id');
        
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'enctype' => 'multipart/form-data', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Element Properties')]);
        $fieldset->addType('image', 'OuterEdge\Layout\Block\Adminhtml\Element\Helper\Image');

        if ($element->getId()) {
            $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
            $fieldset->addField('group_id', 'hidden', ['name' => 'group_id']);
        } elseif ($groupId) {
            $element->setGroupId($groupId);
            $fieldset->addField('group_id', 'hidden', ['name' => 'group_id']);
        }
       
        //Ask template from group code name
        $template = $this->_templates->getAdapter($groupCode);
        $templateData = $template->mappingFields();
        
        //Fixed fields
        $fieldset->addField(
            'title',
            'text',
            [
                'name'  => 'title',
                'label' => __('title'),
                'title' => __('title')
            ]
        );
        
         $fieldset->addField(
            'description',
            'text',
            [
                'name'  => 'description',
                'label' => __('Description'),
                'title' => __('Description')
            ]
        );
        
        
        //Fazer o for each ao contrario!! Primeiro o helper
        //Dynamic fields
        foreach ($element->getData() as $key => $value) {
    
            $fieldType = false;
            foreach ($templateData as $keyTemplate => $field) {
                if ($key == $keyTemplate) {
                    $fieldType = $field;      
                    break;
                }   
            }    

            if ($fieldType) {
                
                switch ($fieldType) {
                    case 'image':
                        $fieldset->addField(
                            $key,
                            'image',
                            [
                                'name'  => $key,
                                'label' => __($key),
                                'title' => __($key),
                                'note'  => 'Allowed types: jpg, jpeg, gif, png, svg'
                            ]
                        );
                        break;
                    case 'description':
                        $fieldset->addField(
                            $key,
                            'editor',
                            [
                                'name'    => $key,
                                'label'   => __($key),
                                'title'   => __($key),
                                'wysiwyg' => true,
                                'config'  => $this->_wysiwygConfig->getConfig([
                                    'hidden'        => $element->getDescription() === strip_tags($element->getDescription()),
                                    'add_variables' => false,
                                    'add_widgets'   => false,
                                    'add_images'    => false
                                ])
                            ]
                        );
                        break;
                    default:
                        $fieldset->addField(
                            $key,
                            $fieldType,
                            [
                                'name'     => $key,
                                'label'    => __($key),
                                'title'    => __($key)
                            ]
                        );
                }
            }
        }
        
        $form->setValues($element->getData());
        $this->setForm($form);

        $this->_eventManager->dispatch('element_form_build_main_tab', ['form' => $form]);

        return parent::_prepareForm();
    }
}
