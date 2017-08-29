<?php

namespace  OuterEdge\Layout\Block\Adminhtml\Group\Edit;

use Magento\Backend\Block\Widget\Tabs as WidgetTabs;

class Tabs extends WidgetTabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('group_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Group Information'));
    }

    /**
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'main',
            [
                'label' => __('Properties'),
                'title' => __('Properties'),
                'content' => $this->getChildHtml('main'),
                'active' => true
            ]
        );

        if ($this->getRequest()->getParam('group_id')) {
            $this->addTab(
                'elements',
                [
                    'label' => __('Manage Elements'),
                    'title' => __('Manage Elements'),
                    'content' => $this->getChildHtml('elements')
                ]
            );
        }

        return parent::_beforeToHtml();
    }
}
