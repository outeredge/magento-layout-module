<?php

namespace OuterEdge\Layout\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use OuterEdge\Layout\Model\TemplateFactory;
use Magento\Framework\Phrase;

abstract class Template extends Action
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
     * @var TemplateFactory
     */
    protected $templateFactory;
    

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param TemplateFactory $templateFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        TemplateFactory $templateFactory
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->resultPageFactory = $resultPageFactory;
        $this->templateFactory = $templateFactory;
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
        $resultPage->addBreadcrumb(__('Template'), __('Template'))
            ->addBreadcrumb(__('Manage Layout Template'), __('Manage Layout Template'))
            ->setActiveMenu('OuterEdge_Layout::template');
        if (!empty($title)) {
            $resultPage->addBreadcrumb($title, $title);
        }
        $resultPage->getConfig()->getTitle()->prepend(__('Layout Template'));
        return $resultPage;
    }
}
