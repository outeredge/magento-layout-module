<?php

namespace OuterEdge\Layout\Block\Adminhtml\GroupTemplate;

use Magento\Backend\Block\Widget\Form\Container;
use Magento\Framework\Registry;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Phrase;

class Edit extends Container
{
    /**
     * Block GroupTemplate name
     *
     * @var string
     */
    protected $_blockGroup = 'OuterEdge_Layout';

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
    }

    /**
     * Initialize GroupTemplate edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'template_id';
        $this->_controller = 'adminhtml_groupTemplate';

        parent::_construct();

        $this->addButton(
            'save_and_edit_button',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                    ],
                ]
            ]
        );

        $this->buttonList->update('save', 'label', __('Save GroupTemplate'));
        $this->buttonList->update('save', 'class', 'save primary');
        $this->buttonList->update(
            'save',
            'data_attribute',
            ['mage-init' => ['button' => ['event' => 'save', 'target' => '#edit_form']]]
        );
    }

    /**
     * Retrieve header text
     *
     * @return Phrase
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('template')->getId()) {
            return __('Edit Template "%1"', $this->escapeHtml($this->_coreRegistry->registry('template')->getTitle()));
        }
        return __('New Template');
    }

    /**
     * Get URL for back (reset) button
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/group/edit', ['group_id' => $this->getGroupId(), 'active_tab' => 'templates']);
    }

    /**
     * Retrieve URL for save
     *
     * @return string
     */
    public function getSaveUrl()
    {
        return $this->getUrl('*/*/save', ['_current' => true, 'back' => null]);
    }

    /**
     * Get group id
     *
     * @return int
     */
    private function getGroupId()
    {
        if ($this->getRequest()->getParam('group_id')) {
            return $this->getRequest()->getParam('group_id');
        }
        return $this->_coreRegistry->registry('template')->getGroupId();
    }
}
