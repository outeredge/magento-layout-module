<?php

namespace OuterEdge\Layout\Controller\Adminhtml\Template;

use OuterEdge\Layout\Controller\Adminhtml\Template;
use Magento\Backend\Model\View\Result\Page;

class Index extends Template
{
    
    /**
     * @return Page
     */
    public function execute()
    {
        $resultPage = $this->createActionPage();
        $resultPage->addContent(
            $resultPage->getLayout()->createBlock('OuterEdge\Layout\Block\Adminhtml\Template')
        );
        return $resultPage;
    }
}
