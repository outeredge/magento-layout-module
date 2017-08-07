<?php

namespace OuterEdge\Layout\Controller\Adminhtml\Groups;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultInterface;
use Exception;

class MassDelete extends Action
{
    /**
     * Delete action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $ids = $this->getRequest()->getParam('group_record_id');
            foreach ($ids as $id) {
                try {
                    $model = $this->_objectManager->create('OuterEdge\Layout\Model\Groups');
                    $model->load($id);
                    $model->delete();

                    $this->messageManager->addSuccess(__('You deleted the groups.'));
                    return $resultRedirect->setPath('*/*/');
                } catch (Exception $e) {
                    $this->messageManager->addError($e->getMessage());
                    return $resultRedirect->setPath('*/*/edit', ['group_id' => $id]);
                }
            }
        }

        $this->messageManager->addError(__('We can\'t find a group to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
