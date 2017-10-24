<?php

namespace OuterEdge\Layout\Controller\Adminhtml\TemplateFields;

use OuterEdge\Layout\Controller\Adminhtml\TemplateFields;
use Magento\Backend\Model\View\Result\Redirect;
use Exception;
use Magento\Framework\App\ResourceConnection;

class Delete extends TemplateFields
{
    /**
     * @return Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('entity_id');
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {
            $model = $this->templateFieldsFactory->create();
            $model->load($id);

            $templateId = $model->getTemplateId();
            $eavAttributeId = $model->getEavAttributeId();
            
            try {
                $model->delete();
                
                //Delete attribute
                $connection = $this->resource->getConnection(ResourceConnection::DEFAULT_CONNECTION);
                $connection->delete(
                    $this->resource->getTableName('eav_attribute'),
                    $connection->quoteInto('attribute_id=?', $eavAttributeId)
                );
                
                $this->messageManager->addSuccess(__('You deleted the field.'));
                return $resultRedirect->setPath(
                    '*/template/edit',
                    ['entity_id' => $templateId,
                    'active_tab' => 'fields']
                );
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['entity_id' => $id]);
            }
        }

        $this->messageManager->addError(__('We can\'t find that field to delete.'));
        return $resultRedirect->setPath('*/*/', ['_current' => true]);
    }
}
