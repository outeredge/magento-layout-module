<?php

namespace OuterEdge\Layout\Block\Adminhtml\Elements\Edit;

use Magento\Backend\Block\Widget\Tabs as WidgetTabs;

class Tabs extends WidgetTabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('element_record');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Elements Information'));
    }
}
