<?php

namespace OuterEdge\Layout\Controller\Adminhtml\Types;

use Magento\Backend\App\Action;

class MassDelete extends \Magento\Backend\App\Action
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
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $ids = $this->getRequest()->getParam('type_record_id');

            foreach ($ids as $id) {
                try {

                    $model = $this->_objectManager->create('OuterEdge\Layout\Model\Types');
                    $model->load($id);
                    $model->delete();

                    $this->messageManager->addSuccess(__('You deleted the type.'));
                    return $resultRedirect->setPath('*/*/');
                } catch (\Exception $e) {
                    $this->messageManager->addError($e->getMessage());
                    return $resultRedirect->setPath('*/*/edit', ['id_type' => $id]);
                }
            }
        }
        $this->messageManager->addError(__('We can\'t find a type to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}