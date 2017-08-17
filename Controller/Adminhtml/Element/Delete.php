<?php

namespace OuterEdge\Layout\Controller\Adminhtml\Element;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultInterface;
use Exception;

class Delete extends Action
{

    /**
     * Delete action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('element_id');

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->_objectManager->create('OuterEdge\Layout\Model\Element');
                $model->load($id);
                $model->delete();

                $this->messageManager->addSuccess(__('You deleted the element.'));
                return $resultRedirect->setPath('layout/group/edit', ['group_id' => $model->getGroupId()]);
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['element_id' => $id]);
            }
        }

        $this->messageManager->addError(__('We can\'t find a element to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
