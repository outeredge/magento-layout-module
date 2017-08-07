<?php

namespace OuterEdge\Layout\Block\Adminhtml\Elements;

use Magento\Backend\Block\Widget\Form\Container;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;
use Magento\Framework\Phrase;
use Magento\Framework\View\Element\AbstractBlock;

class Edit extends Container
{
    /**
     * @var Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
        $this->buttonList->remove('back');
    }

    /**
     * Initialize element edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId   = 'element_id';
        $this->_blockGroup = 'outerEdge_layout';
        $this->_controller = 'adminhtml_elements';

        $this->buttonList->add(
            'back_element',
            [
                'id' => 'back_element',
                'label' => __('Back'),
                'class' => 'action-default scalable back',
                'onclick' => "setLocation('" . $this->_getBackCreateUrl() . "')"
            ]
        );

        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save Elements'));
        $this->buttonList->add(
            'saveandcontinue_elements',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                    ],
                ]
            ],
            -100
        );

        $this->buttonList->update('delete', 'label', __('Delete'));
    }

    /**
     * @return string
     */
    protected function _getBackCreateUrl()
    {
        $idGroup = $this->_coreRegistry->registry('layout_elements_form_data')->getGroupId();
        return $this->getUrl('layout/groups/edit/group_id/' . $idGroup);
    }

    /**
     * Retrieve text for header element depending on loaded post
     *
     * @return Phrase
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('layout_elements_form_data')->getId()) {
            return __("Edit Element '%1'", $this->escapeHtml($this->_coreRegistry->registry('layout_elements_form_data')->getTitle()));
        } else {
            return __('New Element');
        }
    }

    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('*/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '{{tab_id}}']);
    }

    /**
     * Prepare layout
     *
     * @return AbstractBlock
     */
    protected function _prepareLayout()
    {
        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('page_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'content');
                }
            };
        ";
        return parent::_prepareLayout();
    }
}
