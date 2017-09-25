<?php

namespace OuterEdge\Layout\Controller\Adminhtml\TemplateFields;

use OuterEdge\Layout\Controller\Adminhtml\TemplateFields;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use OuterEdge\Layout\Model\TemplateFieldsFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Exception;

class Save extends TemplateFields
{
    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param TemplateFieldsFactory $templateFieldsFactory
     * @param DateTime $dateTime
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        TemplateFieldsFactory $templateFieldsFactory,
        DateTime $dateTime
    ) {
        $this->dateTime = $dateTime;
        parent::__construct(
            $context,
            $coreRegistry,
            $resultPageFactory,
            $templateFieldsFactory
        );
    }

    /**
     * Save action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            $model = $this->templateFieldsFactory->create();

            $templateId = $this->getRequest()->getParam('entity_id');
           
            if ($templateId) {
                $model->load($templateId);

                if (!$model->getId()) {
                    $this->messageManager->addError(__('This template no longer exists.'));
                    return $this->returnResult('*/*/', [], ['error' => true]);
                }
            }
            
            $data = array_map('strtolower', $data);
            $model->addData($data);
            
            try {
                $model->save();

                $this->messageManager->addSuccess(__('The field has been saved.'));

                $this->_session->setTemplateFieldsData(false);

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['entity_id' => $model->getId(), '_current' => true], ['error' => false]);
                }
                return $resultRedirect->setPath('*/template/edit', [
                    'entity_id' => $model->getTemplateId(),
                    'active_tab' => 'fields'
                ], ['error' => false]);
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_session->setGroupData($data);
                return $resultRedirect->setPath('*/*/edit', ['entity_id' => $model->getId(), '_current' => true], ['error' => true]);
            }
        }
        
        //TODO - If field saved is a new attribute, that attribute needs to be created
        
        return $resultRedirect->setPath('*/*/', [], ['error' => true]);
    }
}