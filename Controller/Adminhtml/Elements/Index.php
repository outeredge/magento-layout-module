<?php

namespace OuterEdge\Layout\Controller\Adminhtml\Elements;

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
        $resultPage->setActiveMenu('OuterEdge_Layout::elements');
        $resultPage->addBreadcrumb(__('Layout Elements'), __('Layout Elements'));
        $resultPage->addBreadcrumb(__('Manage Layout Elements'), __('Manage Layout Elements'));
        $resultPage->getConfig()->getTitle()->prepend(__('Layout Elements'));

        return $resultPage;
    }

    /**
     * Is the user allowed to view the elements post grid.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('OuterEdge_Layout::elements');
    }

}