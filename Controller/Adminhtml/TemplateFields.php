<?php

namespace OuterEdge\Layout\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use OuterEdge\Layout\Model\TemplateFieldsFactory;
use OuterEdge\Layout\Setup\ElementSetupFactory;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Phrase;
use Magento\Framework\App\ResourceConnection;

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
     * @var EavConfig
     */
    protected $eavConfig;
    
    /**
     * @var EavSetupFactory
     */
    protected $eavSetupFactory;
    
    /**
     * @var ElementSetupFactory
     */
    protected $elementSetupFactory;
    
    /**
     * @var ResourceConnection
     */
    protected $resource;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param TemplateFieldsFactory $templateFieldsFactory
     * @param EavConfig $eavConfig
     * @param EavSetupFactory $eavSetupFactory
     * @param ElementSetupFactory $elementSetupFactory
     * @param ResourceConnection $resource
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        TemplateFieldsFactory $templateFieldsFactory,
        EavConfig $eavConfig,
        EavSetupFactory $eavSetupFactory,
        ElementSetupFactory $elementSetupFactory,
        ResourceConnection $resource
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->resultPageFactory = $resultPageFactory;
        $this->templateFieldsFactory = $templateFieldsFactory;
        $this->eavConfig = $eavConfig;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->elementSetupFactory = $elementSetupFactory;
        $this->resource = $resource;
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
