<?php

namespace OuterEdge\Layout\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

class Element extends Container
{
    protected function _construct()
    {
        $this->_controller = 'adminhtml_element';
        $this->_blockGroup = 'OuterEdge_Layout';
        $this->_headerText = __('Layout Elements');

        // @todo remove add button
        $this->_addButtonLabel = __('Create New Element');

        parent::_construct();
    }
}
