<?php
namespace  OuterEdge\Layout\Block\Adminhtml\Groups\Edit;

/**
 * Admin page left menu
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('group_record');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Group Information'));
    }
}