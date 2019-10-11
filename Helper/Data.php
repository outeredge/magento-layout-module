<?php

namespace OuterEdge\Layout\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use OuterEdge\Layout\Model\GroupFactory;
use OuterEdge\Layout\Model\Group;
use OuterEdge\Layout\Model\ResourceModel\Group\CollectionFactory as GroupCollectionFactory;
use OuterEdge\Layout\Model\ElementFactory;
use OuterEdge\Layout\Model\Element;
use OuterEdge\Layout\Model\ResourceModel\Element\CollectionFactory as ElementCollectionFactory;
use OuterEdge\Layout\Model\TemplateFieldsFactory;
use OuterEdge\Layout\Model\ResourceModel\TemplateFields\CollectionFactory as TemplateFieldsCollectionFactory;
use OuterEdge\Layout\Model\TemplateFactory;
use OuterEdge\Layout\Model\ResourceModel\Template\CollectionFactory as TemplateCollectionFactory;
use Magento\Eav\Model\Config;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{
    /**
     * @var GroupFactory
     */
    protected $groupFactory;

    /**
     * @var GroupCollectionFactory
     */
    protected $groupCollectionFactory;

    /**
     * @var ElementFactory
     */
    protected $elementFactory;

    /**
     * @var ElementCollectionFactory
     */
    protected $elementCollectionFactory;

    /**
     * @var TemplateFieldsFactory
     */
    protected $templateFieldsFactory;
    
    /**
     * @var TemplateFieldsCollectionFactory
     */
    protected $templateFieldsCollectionFactory;
    
     /**
     * @var TemplateFactory
     */
    protected $templateFactory;
    
    /**
     * @var TemplateCollectionFactory
     */
    protected $templateCollectionFactory;
    
    /**
     * @var Config
     */
    protected $eavConfig;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    
    /**
     * @param Context $context
     * @param GroupFactory $groupFactory
     * @param GroupCollectionFactory $groupCollectionFactory
     * @param ElementFactory $elementFactory
     * @param ElementCollectionFactory $elementCollectionFactory
     * @param TemplateFieldsFactory $templateFieldsFactory
     * @param TemplateFieldsCollectionFactory $templateFieldsCollectionFactory
     * @param TemplateFactory $templateFactory
     * @param TemplateCollectionFactory $templateCollectionFactory
     * @param Config $eavConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        GroupFactory $groupFactory,
        GroupCollectionFactory $groupCollectionFactory,
        ElementFactory $elementFactory,
        ElementCollectionFactory $elementCollectionFactory,
        TemplateFieldsFactory $templateFieldsFactory,
        TemplateFieldsCollectionFactory $templateFieldsCollectionFactory,
        TemplateFactory $templateFactory,
        TemplateCollectionFactory $templateCollectionFactory,
        Config $eavConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->groupFactory = $groupFactory;
        $this->groupCollectionFactory = $groupCollectionFactory;
        $this->elementFactory = $elementFactory;
        $this->elementCollectionFactory = $elementCollectionFactory;
        $this->templateFieldsFactory = $templateFieldsFactory;
        $this->templateFieldsCollectionFactory = $templateFieldsCollectionFactory;
        $this->templateFactory = $templateFactory;
        $this->templateCollectionFactory = $templateCollectionFactory;
        $this->eavConfig = $eavConfig;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * Get store identifier
     *
     * @return  int
     */
    public function getStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }

    /**
     * Get a group by group_code as id field
     *
     * @return Group
     */
    public function getGroup($id = false, $field = 'group_code')
    {
        $group = $this->groupFactory->create()->getCollection();
        if ($id) {

            $group->getSelect()
                ->joinLeft(
                    ['lgs' => 'layout_group_store'],
                    'main_table.entity_id = lgs.group_id',
                    ['lgs.store_id']
                )
                ->where($field.' = ?', $id)
                ->where("(store_id = 0 OR store_id IS NULL OR store_id = ".$this->getStoreId().")")
                ->group('main_table.entity_id');
            $result = $group->getFirstItem();
        }
        return $result;
    }

    /**
     * @return Element
     */
    public function getElement($id = false, $field = null)
    {
        $element = $this->elementFactory->create();
        if ($id) {
            $element->load($id, $field);
        }
        return $element;
    }
    
    /**
     * @return Template
     */
    public function getTemplate($id = false, $field = null)
    {
        $template = $this->templateFactory->create();
        if ($id) {
            $template->load($id, $field);
        }
        return $template;
    }

    /**
     * @return GroupCollection
     */
    public function getGroupCollection()
    {
        return $this->groupCollectionFactory->create();
    }

    /**
     * @return ElementCollection
     */
    public function getElementCollection()
    {
        return $this->elementCollectionFactory->create();
    }

    /**
     * @return TemplateFieldsCollection
     */
    public function getTemplateFieldsCollection()
    {
        return $this->templateFieldsCollectionFactory->create();
    }
    
    /**
     * @return TemplateCollection
     */
    public function getTemplateCollection()
    {
        return $this->templateCollectionFactory->create();
    }
    
    /**
     * @return Group
     */
    public function getGroupAndElements($id, $field = 'group_code')
    {
        $group = $this->getGroup($id, $field);
        $elements = $this->getElementCollection()
            ->addFieldToFilter('group_id', ['eq' => $group->getId()])
            ->setOrder('sort_order', 'ASC');
        
        $elementsData = [];
        foreach ($elements as $element) {
             $data = $this->elementFactory->create();
             $data->load($element->getId());
             $elementsData[] = $data;
        }
       
        $group->setData('elements', $elementsData);
       
        return $group;
    }
    
    /**
     * @return Array
     */
    public function getFieldsTemplate($idGroup)
    {
        //Get template Id from group Id
        $group = $this->getGroup($idGroup, 'entity_id');
        
        $templateFields = $this->getTemplateFieldsCollection()
            ->addFieldToFilter('template_id', ['eq' => $group->getTemplateId()]);
        $templateFields
            ->getSelect()
            ->join(
                ['eav' => 'eav_attribute'],
                'main_table.eav_attribute_id = eav.attribute_id',
                ['eav.attribute_code', 'eav.frontend_label', 'eav.frontend_input']
            );
        $templateFields->setOrder('sort_order', 'ASC');
        
        $templateData = [];
        foreach ($templateFields->getData() as $fields) {
            $templateData[$fields['attribute_code']] = [$fields['frontend_label'] => $fields['frontend_input']];
        }
 
        return $templateData;
    }
    
    public function getTemplates()
    {
        $template = $this->getTemplateCollection();
        $template->setOrder('sort_order', 'ASC');
        
        $data = [];
        foreach ($template->getData() as $temp) {
            $data[$temp['entity_id']] = $temp['code'];
        }
 
        return $data;
    }
    
    /**
     * @return ElementsEav
     */
    public function getElementsWithFieldsByGroup($id, $field = 'group_code')
    {
        $group = $this->getGroup($id, $field);
        $elements = $this->getElementCollection()->addFieldToFilter('group_id', ['eq' => $group->getId()]);
        
        $elementsData = [];
        foreach ($elements as $element) {
             $data = $this->elementFactory->create();
             $data->load($element->getId());
             $elementsData[] = $data->getData();
        }
       
        return $elementsData;
    }
    
    /**
     * Get all attribute options from layout_element entity
     * @return EavConfig
     */
    public function getAttributeOptions($templateId)
    {
        $data = $this->eavConfig->getEntityAttributeCodes('layout_element');
        
        //get identifiers by template
        $templateFields = $this->getTemplateFieldsCollection()
            ->addFieldToFilter('template_id', ['eq' => $templateId]);
        $templateFields
            ->getSelect()
            ->join(
                ['eav' => 'eav_attribute'],
                'main_table.eav_attribute_id = eav.attribute_id',
                ['eav.attribute_code', 'eav.frontend_label', 'eav.frontend_input']
            );
        
        $identifiersInUse = [];
        foreach ($templateFields->getData() as $fields) {
            $identifiersInUse[] = $fields['attribute_code'];
        }
        
        $newData = [];
        foreach ($data as $row) {
            $newData[$row] = $row;
        }
       
        return array_diff($newData, $identifiersInUse);
    }
}
