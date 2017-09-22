<?php

namespace OuterEdge\Layout\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

class TemplateFields extends Container
{
    protected function _construct()
    {
        $this->_controller = 'adminhtml_templateFields';
        $this->_blockGroup = 'OuterEdge_Layout';
        $this->_headerText = __('Layout Template Fields');
        
        parent::_construct();
        
        $this->removeButton('add');
    }
}