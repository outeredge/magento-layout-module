<?php

namespace OuterEdge\Layout\Controller\Adminhtml\GroupTemplate;

use OuterEdge\Layout\Controller\Adminhtml\GroupTemplate;
use Magento\Framework\Controller\ResultInterface;

class Edit extends GroupTemplate
{
    /**
     * @return ResultInterface
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('template_id');

        $model = $this->groupTemplateFactory->create();

        if ($id) {
            $model->load($id);

            if (!$model->getId()) {
                $this->messageManager->addError(__('This template no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_session->getGroupTemplateData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('template', $model);

        $item = $id ? __('Edit Template') : __('New Template');

        $resultPage = $this->createActionPage($item);
        $resultPage->getConfig()->getTitle()->prepend($id ? $model->getTitle() : __('New Template'));
        return $resultPage;
    }
}
