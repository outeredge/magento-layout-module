<?php

namespace OuterEdge\Layout\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Phrase;

class Element extends Container implements TabInterface
{
    /**
     * @var string
     */
    protected $_template = 'element/view.phtml';

    /**
     * @var string
     */
    protected $_idGroup;

    /**
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
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
            $this->getLayout()->createBlock('OuterEdge\Layout\Block\Adminhtml\Element\Grid', 'layout.view.element')
        );
        return parent::_prepareLayout();
    }

    /**
     * @return string
     */
    protected function _getCreateUrl()
    {
        return $this->getUrl('layout/element/new/group_id/' . $this->_idGroup);
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
     * @return Phrase
     */
    public function getTabLabel()
    {
        return __('Element');
    }

    /**
     * Prepare title for tab
     *
     * @return Phrase
     */
    public function getTabTitle()
    {
        return __('Element');
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
