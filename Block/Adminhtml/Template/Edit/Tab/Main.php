<?php

namespace OuterEdge\Layout\Block\Adminhtml\Template\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;

class Main extends Generic
{
    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        array $data = []
    ) {
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
        $template = $this->_coreRegistry->registry('templateModel');

        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Template Properties')]);

        if ($template->getId()) {
            $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
            
            $fieldset->addField(
                'code',
                'text',
                [
                        'name'     => 'template[code]',
                        'label'    => __('Code'),
                        'title'    => __('Code'),
                        'required' => true,
                        'note'  => 'This code represent the template name',
                        'readonly' => true
                    ]
            );
        } else {
            $fieldset->addField(
                'code',
                'text',
                [
                        'name'     => 'template[code]',
                        'label'    => __('Code'),
                        'title'    => __('Code'),
                        'required' => true,
                        'note'  => 'This code represent the template name'
                    ]
            );
        }
        
        $fieldset->addField(
            'sort_order',
            'text',
            [
                'name'  => 'template[sort_order]',
                'label' => __('Sort Order'),
                'title' => __('Sort Order')
            ]
        );

        $form->setValues($template->getData());
        $this->setForm($form);

        $this->_eventManager->dispatch('template_form_build_main_tab', ['form' => $form]);

        return parent::_prepareForm();
    }
}
