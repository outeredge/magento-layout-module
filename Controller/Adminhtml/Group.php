<?php

namespace OuterEdge\Layout\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use OuterEdge\Layout\Model\GroupFactory;
use Magento\Framework\Phrase;

abstract class Group extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'OuterEdge_Layout::layout';

    /**
     * @var Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var GroupFactory
     */
    protected $groupFactory;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param GroupFactory $groupFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        GroupFactory $groupFactory
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->resultPageFactory = $resultPageFactory;
        $this->groupFactory = $groupFactory;
        parent::__construct($context);
    }

    /**
     * @param Phrase|null $title
     * @return Page
     */
    protected function createActionPage($title = null)
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->addBreadcrumb(__('Group'), __('Group'))
            ->addBreadcrumb(__('Manage Layout Groups'), __('Manage Layout Groups'))
            ->setActiveMenu('OuterEdge_Layout::group');
        if (!empty($title)) {
            $resultPage->addBreadcrumb($title, $title);
        }
        $resultPage->getConfig()->getTitle()->prepend(__('Layout Groups'));
        return $resultPage;
    }
}
