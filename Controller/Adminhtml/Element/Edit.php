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
        $group = $this->getRequest()->getParam('group_id');
        
        $data = $this->dataProcess($id, $group);

        $model = $this->elementFactory->create();

        if ($id) {
            $model->load($id);

            if (!$model->getId()) {
                $this->messageManager->addError(__('This element no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

      //  $data = $this->_session->getElementData(true);
        
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('element', $model);

        $item = $id ? __('Edit Element') : __('New Element');

        $resultPage = $this->createActionPage($item);
        $resultPage->getConfig()->getTitle()->prepend($id ? $model->getTitle() : __('New Element'));
        return $resultPage;
    }
    
    //TODO CREATE A HELPER WITH THIS
    public function dataProcess($id, $group)
    {
         //Get element from group and template 
        $collection = $this->elementFactory->create()->getCollection();
        $collection->addFieldToFilter('main_table.element_id', ['eq' => $id]);
        $collection->addFieldToFilter('main_table.group_id', ['eq' => $group]);
        $collection->getSelect()->join('layout_group_template as lgt', 'main_table.template_id = lgt.template_id', ['lgt.label', 'lgt.type']);
        
      /*  $newArray = [];
        foreach ($collection->getData() as $row) {
            $newArray[$row['label']] = [$row['type'] => $row['content']];
        }
        
        print_r($newArray); */
        
        return $collection->getData();
         
    }
}
