<?php

namespace OuterEdge\Layout\Block\Adminhtml\Template\Edit;

use Magento\Backend\Block\Widget\Tabs as WidgetTabs;

class Tabs extends WidgetTabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('template_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Template Information'));
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

        if ($this->getRequest()->getParam('entity_id')) {
            $this->addTab(
                'fields',
                [
                    'label' => __('Manage Fields'),
                    'title' => __('Manage Fields'),
                    'content' => $this->getChildHtml('fields')
                ]
            );
        }

        return parent::_beforeToHtml();
    }
}
