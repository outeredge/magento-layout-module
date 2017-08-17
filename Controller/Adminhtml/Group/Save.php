<?php

namespace OuterEdge\Layout\Controller\Adminhtml\Group;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use RuntimeException;
use Exception;

class Save extends Action
{
    /**
     * @var DateTime
     */
    protected $datetime;

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context,
        DateTime $datetime
    ) {
        $this->datetime = $datetime;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $post = $this->getRequest()->getPostValue();

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($post) {
            $model = $this->_objectManager->create('OuterEdge\Layout\Model\Group');
            $data = $this->getRequest()->getParam('group');

            if (isset($data['group_id'])) {
                $model->load($data['group_id']);
            } else {
                $data['created_at'] = $this->datetime->date();
            }

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccess(__('The data has been saved.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['group_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['group_id' => $this->getRequest()->getParam('group_record_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
