<?php

namespace OuterEdge\Layout\Block\Adminhtml\GroupTemplate\Edit\Tab;

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
        $template = $this->_coreRegistry->registry('template');

        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Template Properties')]);

        if ($template->getId()) {
            $fieldset->addField('template_id', 'hidden', ['name' => 'template_id']);
        }

        $fieldset->addField(
            'label',
            'text',
            [
                'name'  => 'label',
                'label' => __('Label'),
                'title' => __('Label')
            ]
        );
        
        $fieldset->addField(
            'type',
            'text',
            [
                'name'  => 'type',
                'label' => __('Type'),
                'title' => __('Type')
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

        $form->setValues($template->getData());
        $this->setForm($form);

        $this->_eventManager->dispatch('template_form_build_main_tab', ['form' => $form]);

        return parent::_prepareForm();
    }
}
