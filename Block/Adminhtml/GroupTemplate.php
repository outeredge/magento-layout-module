<?php

namespace OuterEdge\Layout\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

class GroupTemplate extends Container
{
    protected function _construct()
    {
        $this->_controller = 'adminhtml_groupTemplate';
        $this->_blockGroup = 'OuterEdge_Layout';
        $this->_headerText = __('Layout Groups Template');
        $this->_addButtonLabel = __('Create New Template');
        parent::_construct();
    }
}
