<?php

namespace OuterEdge\Layout\Controller\Adminhtml\Template;

use OuterEdge\Layout\Controller\Adminhtml\Template;
use Magento\Backend\Model\View\Result\Redirect;
use Exception;

class Delete extends Template
{
    /**
     * @return Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('entity_id');
       
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {
            $model = $this->templateFactory->create();
            $model->load($id);

            try {
                $model->delete();
                $this->messageManager->addSuccess(__('You deleted the template.'));
                return $resultRedirect->setPath('*/*/');
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['entity_id' => $id]);
            }
        }
        
        $this->messageManager->addError(__('We can\'t find a template to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
