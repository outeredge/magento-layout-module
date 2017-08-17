<?php

namespace OuterEdge\Layout\Controller\Adminhtml\Group;

use OuterEdge\Layout\Controller\Adminhtml\Group;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use OuterEdge\Layout\Model\GroupFactory;
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
     * @param DateTime $dateTime
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        GroupFactory $groupFactory,
        DateTime $dateTime
    ) {
        $this->dateTime = $dateTime;
        parent::__construct(
            $context,
            $coreRegistry,
            $resultPageFactory,
            $groupFactory
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
            $groupId = $this->getRequest()->getParam('group_id');

            $data['updated_at'] = $this->dateTime->date();

            $model = $this->groupFactory->create();

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

                $this->messageManager->addSuccess(__('You saved the group.'));

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
