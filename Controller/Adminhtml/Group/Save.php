<?php

namespace OuterEdge\Layout\Controller\Adminhtml\Group;

use OuterEdge\Layout\Controller\Adminhtml\Group;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use OuterEdge\Layout\Model\GroupFactory;
use OuterEdge\Layout\Model\ElementFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Exception;

class Save extends Group
{
    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param GroupFactory $groupFactory
     * @param ElementFactory $elementFactory
     * @param DateTime $dateTime
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        GroupFactory $groupFactory,
        ElementFactory $elementFactory,
        DateTime $dateTime
    ) {
        $this->dateTime = $dateTime;
        parent::__construct(
            $context,
            $coreRegistry,
            $resultPageFactory,
            $groupFactory,
            $elementFactory
        );
    }

    /**
     * Save action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue('group');
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            $data['updated_at'] = $this->dateTime->date();

            $model = $this->groupFactory->create();

            $groupId = $this->getRequest()->getParam('entity_id');
           
            if ($groupId) {
                $model->load($groupId);

                if (!$model->getId()) {
                    $this->messageManager->addError(__('This group no longer exists.'));
                    return $this->returnResult('*/*/', [], ['error' => true]);
                }
            } else {
                $data['created_at'] = $this->dateTime->date();
            }

            $model->addData($data);

            try {
                $model->save();

                $this->messageManager->addSuccess(__('The group has been saved.'));

                $this->_session->setGroupData(false);

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['group_id' => $model->getId(), '_current' => true], ['error' => false]);
                }
                return $resultRedirect->setPath('*/*/', [], ['error' => false]);
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_session->setGroupData($data);
                return $resultRedirect->setPath('*/*/edit', ['group_id' => $model->getId(), '_current' => true], ['error' => true]);
            }
        }
        return $resultRedirect->setPath('*/*/', [], ['error' => true]);
    }
}
