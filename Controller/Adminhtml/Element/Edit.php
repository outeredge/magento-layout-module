<?php

namespace OuterEdge\Layout\Controller\Adminhtml\Element;

use OuterEdge\Layout\Controller\Adminhtml\Element;
use Magento\Framework\Controller\ResultInterface;

class Edit extends Element
{
    /**
     * @return ResultInterface
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('element_id');
        $groupId = $this->getRequest()->getParam('entity_id');

        $model = $this->elementFactory->create();

        if ($id) {
            $model->load($id);

            if (!$model->getId()) {
                $this->messageManager->addError(__('This element no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_session->getElementData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $groupModel = $this->groupFactory->create();
        if ($groupId) {
            $groupModel->load($groupId);   
        } else {
            $groupModel->load($model->getGroupId());
        }
        $this->_coreRegistry->register('groupCode', $groupModel->getGroupCode());
        
        $this->_coreRegistry->register('elementModel', $model);

        $item = $id ? __('Edit Element') : __('New Element');

        $resultPage = $this->createActionPage($item);
        $resultPage->getConfig()->getTitle()->prepend($id ? $model->getTitle() : __('New Element'));
        return $resultPage;
    }
}
