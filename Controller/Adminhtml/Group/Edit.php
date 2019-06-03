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
        $id = $this->getRequest()->getParam('entity_id');

        $model = $this->groupFactory->create()->getCollection();
        $model->getSelect()
            ->joinLeft(
                ['lgs' => 'layout_group_store'],
                'main_table.entity_id = lgs.group_id',
                ['GROUP_CONCAT(`lgs`.`store_id`) as store_ids']
            )->group('main_table.entity_id');

//TODO inner join return more than one row
//We can create a new model do query layout_group_store and add the store field to $data
//But in the frontend, when we need to be filter by store...

         //   echo ($model->getSelect());
//   die();
        if ($id) {
            $model->getSelect()->where('main_table.entity_id = ?', $id);
            $model = $model->getFirstItem();

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

        $this->_coreRegistry->register('groupModel', $model);

        $item = $id ? __('Edit Group') : __('New Group');

        $resultPage = $this->createActionPage($item);
        $resultPage->getConfig()->getTitle()->prepend($id ? $model->getTitle() : __('New Group'));
        return $resultPage;
    }
}
