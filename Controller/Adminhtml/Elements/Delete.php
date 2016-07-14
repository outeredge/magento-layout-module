<?php

namespace OuterEdge\Layout\Controller\Adminhtml\Elements;

use Magento\Backend\App\Action;

class Delete extends \Magento\Backend\App\Action
{

    /**
     * @param Action\Context $context
     */
    public function __construct(Action\Context $context)
    {
        parent::__construct($context);
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('element_id');

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {

                $model = $this->_objectManager->create('OuterEdge\Layout\Model\Elements');
                $model->load($id);
                $model->delete();

                $this->messageManager->addSuccess(__('You deleted the element.'));
                return $resultRedirect->setPath('layout/groups/edit', ['group_id' => $model->getFkGroup()]);
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['element_id' => $id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find a element to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}