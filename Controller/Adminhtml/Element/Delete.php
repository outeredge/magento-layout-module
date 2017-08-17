<?php

namespace OuterEdge\Layout\Controller\Adminhtml\Element;

use OuterEdge\Layout\Controller\Adminhtml\Element;
use Magento\Backend\Model\View\Result\Redirect;
use Exception;

class Delete extends Element
{
    /**
     * @return Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('element_id');
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {
            $model = $this->elementFactory->create();
            $model->load($id);

            try {
                $model->delete();
                $this->messageManager->addSuccess(__('You deleted the element.'));
                return $resultRedirect->setPath('*/*/');
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['element_id' => $id]);
            }
        }
        
        $this->messageManager->addError(__('We can\'t find a element to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
