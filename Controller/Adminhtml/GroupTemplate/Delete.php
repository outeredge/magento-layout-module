<?php

namespace OuterEdge\Layout\Controller\Adminhtml\GroupTemplate;

use OuterEdge\Layout\Controller\Adminhtml\GroupTemplate;
use Magento\Backend\Model\View\Result\Redirect;
use Exception;

class Delete extends GroupTemplate
{
    /**
     * @return Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('template_id');
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {
            $model = $this->elementFactory->create();
            $model->load($id);

            $groupId = $model->getGroupId();

            try {
                $model->delete();
                $this->messageManager->addSuccess(__('You deleted the element.'));
                return $resultRedirect->setPath('*/group/edit', ['group_id' => $groupId, 'active_tab' => 'templates']);
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['template_id' => $id, 'group_id' => $groupId]);
            }
        }

        $this->messageManager->addError(__('We can\'t find a template to delete.'));
        return $resultRedirect->setPath('*/*/', ['_current' => true]);
    }
}
