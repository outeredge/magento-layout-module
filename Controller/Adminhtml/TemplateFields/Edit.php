<?php

namespace OuterEdge\Layout\Controller\Adminhtml\TemplateFields;

use OuterEdge\Layout\Controller\Adminhtml\TemplateFields;
use Magento\Framework\Controller\ResultInterface;

class Edit extends TemplateFields
{
     /**
     * @return ResultInterface
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('entity_id');
        $templateId = $this->getRequest()->getParam('template_id');
 
        $model = $this->templateFieldsFactory->create();

        if ($id) {
            $model->load($id);

            if (!$model->getId()) {
                $this->messageManager->addError(__('This field no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_session->getTemplateFieldsData(true);
        
        if (!empty($data)) {
            $model->setData($data);
        }

        /*$templateModel = $this->groupFactory->create();
        if ($templateId) {
            $templateModel->load($templateId);   
        } else {
            $templateModel->load($model->gettemplateId());
        }
        
        $this->_coreRegistry->register('groupCode', $templateModel->getGroupCode()); */
        
        $this->_coreRegistry->register('templateFieldsModel', $model);

        $item = $id ? __('Edit Field') : __('New Field');

        $resultPage = $this->createActionPage($item);
        $resultPage->getConfig()->getTitle()->prepend($id ? $model->getTitle() : __('New Field'));
        return $resultPage;
    }
}
