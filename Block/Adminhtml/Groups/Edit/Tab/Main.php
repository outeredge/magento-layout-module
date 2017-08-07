<?php

namespace OuterEdge\Layout\Block\Adminhtml\Groups\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Store\Model\System\Store;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Phrase;

class Main extends Generic implements TabInterface
{
    /**
     * @var Store
     */
    protected $_systemStore;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Store $systemStore
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Store $systemStore,
        array $data = []
    ) {
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
        $model = $this->_coreRegistry->registry('layout_groups_form_data');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('groups_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Edit Group')]);

        if ($model->getId()) {
            $fieldset->addField('group_id', 'hidden', ['name' => 'group[group_id]']);
        }

        $fieldset->addField(
            'group_code',
            'text',
            [
                'name' => 'group[group_code]',
                'label' => __('Group Code'),
                'title' => __('Group Code'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'title',
            'text',
            [
                'name' => 'group[title]',
                'label' => __('Title'),
                'title' => __('Title'),
                'required' => true
            ]
        );
        $fieldset->addField(
            'description',
            'textarea',
            [
                'name' => 'group[description]',
                'label' => __('Description'),
                'title' => __('Description'),
                'required' => true
            ]
        );
        $fieldset->addField(
            'sort_order',
            'text',
            [
                'name' => 'group[sort_order]',
                'label' => __('Sort Order'),
                'title' => __('Sort Order'),
                'required' => false
            ]
        );

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return Phrase
     */
    public function getTabLabel()
    {
        return __('Group Information');
    }

    /**
     * Prepare title for tab
     *
     * @return Phrase
     */
    public function getTabTitle()
    {
        return __('Group Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
