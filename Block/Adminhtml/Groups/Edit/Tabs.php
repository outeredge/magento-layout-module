<?php

namespace  OuterEdge\Layout\Block\Adminhtml\Groups\Edit;

use Magento\Backend\Block\Widget\Tabs as WidgetTabs;

class Tabs extends WidgetTabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('group_record');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Information'));
    }
}
