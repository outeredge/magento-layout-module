<?php

namespace OuterEdge\Layout\Block\Adminhtml\TemplateFields;

use Magento\Backend\Block\Widget\Form\Container;
use Magento\Framework\Registry;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Phrase;

class Edit extends Container
{
    /**
     * Block group name
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
     * Initialize TemplateFields edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'entity_id';
        $this->_controller = 'adminhtml_templateFields';

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

        $this->buttonList->update('save', 'label', __('Save Field'));
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
        if ($this->_coreRegistry->registry('templateFields')->getId()) {
            return __(
                'Edit Field "%1"',
                $this->escapeHtml($this->_coreRegistry->registry('templateFields')->getTemplateFields())
            );
        }
        return __('New Template Fields');
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
     * Get URL for back (reset) button
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/template/edit', ['entity_id' => $this->getTemplateId(), 'active_tab' => 'fields']);
    }
    
     /**
     * Get template id
     *
     * @return int
     */
    private function getTemplateId()
    {
        if ($this->getRequest()->getParam('template_id')) {
            return $this->getRequest()->getParam('template_id');
        }
        return $this->_coreRegistry->registry('templateFieldsModel')->getTemplateId();
    }
}
