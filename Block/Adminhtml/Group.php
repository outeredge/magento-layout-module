<?php

namespace OuterEdge\Layout\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

class Group extends Container
{
    /**
     * @var string
     */
    protected $_template = 'group/view.phtml';

    protected function _prepareLayout()
    {
        $addButtonProps = [
            'id' => 'add_new_grid',
            'label' => __('Add New Group'),
            'class' => 'add',
            'button_class' => '',
            'class_name' => 'Magento\Backend\Block\Widget\Button\SplitButton',
            'options' => $this->_getAddButtonOptions(),
        ];
        $this->buttonList->add('add_new', $addButtonProps);

        $this->setChild(
            'grid',
            $this->getLayout()->createBlock('OuterEdge\Layout\Block\Adminhtml\Group\Grid', 'layout.view.group')
        );
        return parent::_prepareLayout();
    }

    /**
     * @return array
     */
    protected function _getAddButtonOptions()
    {
        $splitButtonOptions[] = [
            'label' => __('Add New Group'),
            'onclick' => "setLocation('" . $this->_getCreateUrl() . "')"
        ];
        return $splitButtonOptions;
    }


    /**
     * @return string
     */
    protected function _getCreateUrl()
    {
        return $this->getUrl(
            'layout/*/new'
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
}
