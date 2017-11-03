<?php

namespace OuterEdge\Layout\Block\Adminhtml\Element\Edit;

use Magento\Backend\Block\Widget\Tabs as WidgetTabs;

class Tabs extends WidgetTabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('element_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Element Information'));
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

        return parent::_beforeToHtml();
    }
}
