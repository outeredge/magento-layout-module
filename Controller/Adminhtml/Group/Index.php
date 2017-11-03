<?php

namespace OuterEdge\Layout\Controller\Adminhtml\Group;

use OuterEdge\Layout\Controller\Adminhtml\Group;
use Magento\Backend\Model\View\Result\Page;

class Index extends Group
{
    
    /**
     * @return Page
     */
    public function execute()
    {
        $resultPage = $this->createActionPage();
        $resultPage->addContent(
            $resultPage->getLayout()->createBlock('OuterEdge\Layout\Block\Adminhtml\Group')
        );
        return $resultPage;
    }
}
