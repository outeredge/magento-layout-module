<?php

namespace OuterEdge\Layout\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

class Template extends Container
{
    protected function _construct()
    {
        $this->_controller = 'adminhtml_template';
        $this->_blockGroup = 'OuterEdge_Layout';
        $this->_headerText = __('Layout Template');
        $this->_addButtonLabel = __('Create New Template');
        parent::_construct();
    }
}
