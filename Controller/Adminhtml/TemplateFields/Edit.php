<?php

namespace OuterEdge\Layout\Controller\Adminhtml\TemplateFields;

use OuterEdge\Layout\Controller\Adminhtml\TemplateFields;
use Magento\Framework\Controller\ResultInterface;

class Edit extends TemplateFields
{
     /**
     * @return ResultInterface
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('entity_id');
        $templateId = $this->getRequest()->getParam('template_id');
 
        $model = $this->templateFieldsFactory->create()->getCollection();
        $model->getSelect()
            ->join(
                array('eav' => 'eav_attribute'),
                'main_table.eav_attribute_id = eav.attribute_id',
                array('eav.attribute_code', 'eav.frontend_label', 'eav.frontend_input')
            );
            
        if ($id) {
            $model->getSelect()->where('main_table.entity_id = ?', $id);
            $model = $model->getFirstItem();
        
            if (!$model->getId()) {
               $this->messageManager->addError(__('This field no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        } else {
            $model = $this->templateFieldsFactory->create(); 
        }
        
        $data = $this->_session->getTemplateFieldsData(true);
        
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('templateFieldsModel', $model);

        $item = $id ? __('Edit Field') : __('New Field');

        $resultPage = $this->createActionPage($item);
        $resultPage->getConfig()->getTitle()->prepend($id ? $model->getTitle() : __('New Field'));
        return $resultPage;
    }
}
