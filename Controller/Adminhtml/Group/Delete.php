<?php

namespace OuterEdge\Layout\Controller\Adminhtml\Group;

use OuterEdge\Layout\Controller\Adminhtml\Group;
use Magento\Backend\Model\View\Result\Redirect;
use Exception;

class Delete extends Group
{
    /**
     * @return Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('entity_id');
       
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {
            $model = $this->groupFactory->create();
            $model->load($id);

            try {
                $model->delete();
                $this->messageManager->addSuccess(__('You deleted the group.'));
                return $resultRedirect->setPath('*/*/');
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['entity_id' => $id]);
            }
        }
        
        $this->messageManager->addError(__('We can\'t find a group to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
