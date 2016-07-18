<?php

namespace OuterEdge\Layout\Block\Adminhtml\Groups\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

/**
 * Groups edit form main tab
 */
class Main extends Generic implements TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
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
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Group Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
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