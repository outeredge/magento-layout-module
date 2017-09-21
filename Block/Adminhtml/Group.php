<?php

namespace OuterEdge\Layout\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

class Group extends Container
{
    protected function _construct()
    {
        $this->_controller = 'adminhtml_group';
        $this->_blockGroup = 'OuterEdge_Layout';
        $this->_headerText = __('Layout Groups');
        $this->_addButtonLabel = __('Create New Group');
        parent::_construct();
    }
}
