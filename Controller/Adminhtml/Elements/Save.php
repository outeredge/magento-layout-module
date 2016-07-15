<?php

namespace OuterEdge\Layout\Controller\Adminhtml\Elements;

use Magento\Backend\App\Action;
use Magento\Framework\Stdlib\DateTime\DateTime;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var DateTime
     */
    protected $datetime;

    /**
     * @param Action\Context $context
     */
    public function __construct(
        Action\Context $context,
        DateTime $datetime)
    {
        $this->datetime = $datetime;

        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model = $this->_objectManager->create('OuterEdge\Layout\Model\Elements');

            $id = $this->getRequest()->getParam('element_id');

            if ($id) {
                $model->load($id);
            } else {
                $data['created_at'] = $this->datetime->date();
            }

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccess(__('The data has been saved.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('layout/groups/edit', ['group_id' => $model->getFkGroup(), '_current' => true]);
                }
                return $resultRedirect->setPath('layout/groups/edit',['group_id' => $model->getFkGroup()]);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('layout/groups/edit', ['group_id' => $model->getFkGroup()]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}