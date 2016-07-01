<?php

namespace OuterEdge\Layout\Controller\Adminhtml\Types;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
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
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('OuterEdge_Layout::types');
        $resultPage->addBreadcrumb(__('Layout Types'), __('Layout Types'));
        $resultPage->addBreadcrumb(__('Manage Layout Types'), __('Manage Layout Types'));
        $resultPage->getConfig()->getTitle()->prepend(__('Layout Types'));

        return $resultPage;
    }

    /**
     * Is the user allowed to view the types post grid.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('OuterEdge_Layout::types');
    }

}