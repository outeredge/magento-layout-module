<?php

namespace OuterEdge\Layout\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;
use Magento\Backend\Block\Widget\Tab\TabInterface;
/**
 * Elements edit form main tab
 */
class Elements extends Container implements TabInterface
{
    protected $_template = 'elements/view.phtml';

    protected $_idGroup;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->buttonList->remove('add');
    }

    protected function _prepareLayout()
    {
        $this->_idGroup = $this->getRequest()->getParam('group_id');

        $addButtonProps = [
            'id' => 'add_new_grid',
            'label' => __('Add New Element'),
            'class' => 'action-default scalable add primary',
            'onclick' => "setLocation('" . $this->_getCreateUrl() . "')"
        ];
        $this->buttonList->add('add_new', $addButtonProps);

        $this->setChild(
            'grid',
            $this->getLayout()->createBlock('OuterEdge\Layout\Block\Adminhtml\Elements\Grid', 'layout.view.elements')
        );
        return parent::_prepareLayout();
    }

    /**
     *
     *
     * @param string $type
     * @return string
     */
    protected function _getCreateUrl()
    {
        return $this->getUrl(
            "layout/elements/new/group_id/$this->_idGroup"
        );
    }

    /**
     * Render grid
     *
     * @return string
     */
    public function getGridHtml()
    {
        return $this->getChildHtml('grid');
    }


    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Elements');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Elements');
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