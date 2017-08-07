<?php

namespace OuterEdge\Layout\Controller\Adminhtml\Groups;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\View\Result\Page;

class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('OuterEdge_Layout::groups')
            ->addBreadcrumb(__('Layout Groups'), __('Layout Groups'))
            ->addBreadcrumb(__('Manage Layout Groups'), __('Manage Layout Groups'));
        $resultPage->getConfig()->getTitle()->prepend(__('Layout Groups'));
        return $resultPage;
    }

    /**
     * Is the user allowed to view the groups post grid.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('OuterEdge_Layout::groups');
    }
}
