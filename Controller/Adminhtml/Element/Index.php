<?php

namespace OuterEdge\Layout\Controller\Adminhtml\Element;

use OuterEdge\Layout\Controller\Adminhtml\Element;
use Magento\Backend\Model\View\Result\Page;

class Index extends Element
{
    /**
     * @return Page
     */
    public function execute()
    {
        $resultPage = $this->createActionPage();
        $resultPage->addContent(
            $resultPage->getLayout()->createBlock('OuterEdge\Layout\Block\Adminhtml\Element')
        );
        return $resultPage;
    }
}
