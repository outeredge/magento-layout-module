<?php

namespace OuterEdge\Layout\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use OuterEdge\Layout\Model\TemplateFieldsFactory;
use Magento\Framework\Phrase;

abstract class TemplateFields extends Action
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
     * @var TemplateFieldsFactory
     */
    protected $templateFieldsFactory;
    

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param TemplateFieldsFactory $templateFieldsFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        TemplateFieldsFactory $templateFieldsFactory
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->resultPageFactory = $resultPageFactory;
        $this->templateFieldsFactory = $templateFieldsFactory;
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
        $resultPage->addBreadcrumb(__('Template Fields'), __('Template Fields'))
            ->addBreadcrumb(__('Manage Layout Template Fields'), __('Manage Layout Template Fields'))
            ->setActiveMenu('OuterEdge_Layout::templateFields');
        if (!empty($title)) {
            $resultPage->addBreadcrumb($title, $title);
        }
        $resultPage->getConfig()->getTitle()->prepend(__('Layout template Fields'));
        return $resultPage;
    }
}
