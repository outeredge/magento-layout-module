<?php

namespace OuterEdge\Layout\Controller\Adminhtml\Group;

use OuterEdge\Layout\Controller\Adminhtml\Group;
use Magento\Framework\Controller\ResultInterface;

class Edit extends Group
{
    /**
     * @return ResultInterface
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('group_id');

        $model = $this->groupFactory->create();

        if ($id) {
            $model->load($id);

            if (!$model->getId()) {
                $this->messageManager->addError(__('This group no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_session->getGroupData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('group', $model);

        $item = $id ? __('Edit Group') : __('New Group');

        $resultPage = $this->createActionPage($item);
        $resultPage->getConfig()->getTitle()->prepend($id ? $model->getTitle() : __('New Group'));
        return $resultPage;
    }
}
