<?php

namespace OuterEdge\Layout\Controller\Adminhtml\TemplateFields;

use OuterEdge\Layout\Controller\Adminhtml\TemplateFields;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use OuterEdge\Layout\Model\TemplateFieldsFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Eav\Setup\EavSetupFactory;
use OuterEdge\Layout\Setup\ElementSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\App\ResourceConnection;
use Zend_Validate_Regex;
use Exception;
use Magento\Eav\Model\Entity\Attribute;

class Save extends TemplateFields
{
    /**
     * @var ResourceConnection
     */
    private $resource;
    
    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * @var Attribute
     */
    private $eavAttribute;
    
    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param TemplateFieldsFactory $templateFieldsFactory
     * @param DateTime $dateTime
     * @param EavConfig $eavConfig
     * @param EavSetupFactory $eavSetupFactory
     * @param ElementSetupFactory $elementSetupFactory
     * @param ResourceConnection $resource
     * @param Attribute $eavAttribute
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        TemplateFieldsFactory $templateFieldsFactory,
        DateTime $dateTime,
        EavConfig $eavConfig,
        EavSetupFactory $eavSetupFactory,
        ElementSetupFactory $elementSetupFactory,
        ResourceConnection $resource,
        Attribute $eavAttribute
    ) {
        $this->dateTime = $dateTime;
        $this->resource = $resource;
        $this->eavAttribute = $eavAttribute;
        parent::__construct(
            $context,
            $coreRegistry,
            $resultPageFactory,
            $templateFieldsFactory,
            $eavConfig,
            $eavSetupFactory,
            $elementSetupFactory
        );
    }

    /**
     * Save action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            $model = $this->templateFieldsFactory->create();

            $fieldId = $this->getRequest()->getParam('entity_id');
           
            if ($fieldId) {
                $model->load($fieldId);

                if (!$model->getId()) {
                    $this->messageManager->addError(__('This template no longer exists.'));
                    return $this->returnResult('*/*/', [], ['error' => true]);
                }
            }
            
            //EAV Attribute
            if ($fieldId) {
                //Update attribute
                $this->updateEavAttribute($data);
            } else {
                //Create attribute
                $attributeCode = $data['template_code'] .'_'. $data['attribute_code'];
                if (strlen($attributeCode) > 0) {
                    $validatorAttrCode = new Zend_Validate_Regex(['pattern' => '/^[a-z][a-z_0-9]{0,30}$/']);
                    if (!$validatorAttrCode->isValid($attributeCode)) {
                        $this->messageManager->addError(
                            __(
                                'Attribute code "%1" is invalid. Please use only letters (a-z), ' .
                                'numbers (0-9) or underscore(_) in this field, first character should be a letter.',
                                $attributeCode
                            )
                        );
                        return $this->returnResult('*/*/', [], ['error' => true]);
                    }
                }
                $data['attribute_code'] = $attributeCode;
                
                $eavAttributeId = $this->createEavAttribute($data);
                
                if (!$eavAttributeId) {
                    $this->messageManager->addError(__('Error creating new attribute.'));
                    return $this->returnResult('*/*/', [], ['error' => true]);
                }
                
                $data += ['eav_attribute_id' => $eavAttributeId];
            }
            
            $data = array_map('strtolower', $data);
            $model->addData($data);
            
            try {
                $model->save();

                $this->messageManager->addSuccess(__('The field has been saved.'));

                $this->_session->setTemplateFieldsData(false);

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath(
                        '*/*/edit',
                        ['entity_id' => $model->getId(),
                        '_current' => true],
                        ['error' => false]
                    );
                }
                return $resultRedirect->setPath('*/template/edit', [
                    'entity_id' => $model->getTemplateId(),
                    'active_tab' => 'fields'
                ], ['error' => false]);
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_session->setGroupData($data);
                return $resultRedirect->setPath(
                    '*/*/edit',
                    ['entity_id' => $model->getId(),
                    '_current' => true],
                    ['error' => true]
                );
            }
        }
        return $resultRedirect->setPath('*/*/', [], ['error' => true]);
    }
    
    /**
     * return $eav_attribute_id
     */
    public function createEavAttribute($row)
    {
        $elementEntity = \OuterEdge\Layout\Model\Element::ENTITY;
        $elementSetup = $this->elementSetupFactory->create();
        $elementSetup->addAttribute(
            $elementEntity,
            $row['attribute_code'],
            [
            'type' => $this->getBackendTypeByInput($row['frontend_input'])
            ]
        );
        
        $connection = $this->resource->getConnection(ResourceConnection::DEFAULT_CONNECTION);
        $select = $connection->select()->from(
            $this->resource->getTableName('eav_attribute')
        )->where('attribute_code = :attribute_code');
        $result = $connection->fetchRow($select, ['attribute_code' => $row['attribute_code']]);
      
        $data = [
            'frontend_label' => $row['frontend_label'],
            'frontend_input' => $row['frontend_input']
        ];
        
        if ($result['attribute_id']) {
            $connection->update(
                $this->resource->getTableName('eav_attribute'),
                $data,
                $connection->quoteInto('attribute_id=?', $result['attribute_id'])
            );
            
            return $result['attribute_id'];
        }
        return false;
    }
    
    public function updateEavAttribute($row)
    {
        $data = [
            'frontend_label' => $row['frontend_label'],
            'frontend_input' => $row['frontend_input']
        ];
        
        $connection = $this->resource->getConnection(ResourceConnection::DEFAULT_CONNECTION);
        $connection->update(
            $this->resource->getTableName('eav_attribute'),
            $data,
            $connection->quoteInto('attribute_id=?', $row['eav_attribute_id'])
        );
    }
    
    /**
     * Detect backend storage type using frontend input type
     *
     * @param string $type frontend_input field value
     * @return string backend_type field value
     */
    protected function getBackendTypeByInput($type)
    {
        switch ($type) {
            case 'editor':
                $field = 'text';
                break;
            default:
                $field = $this->eavAttribute->getBackendTypeByInput($type);
                break;
        }
        
        return $field;
    }
}
