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
     * @param Context $context
     * @param GroupFactory $groupFactory
     * @param GroupCollectionFactory $groupCollectionFactory
     * @param ElementFactory $elementFactory
     * @param ElementCollectionFactory $elementCollectionFactory
     * @param TemplateFieldsFactory $templateFieldsFactory
     * @param TemplateFieldsCollectionFactory $templateFieldsCollectionFactory
     * @param TemplateFactory $templateFactory
     * @param TemplateCollectionFactory $templateCollectionFactory
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
        TemplateCollectionFactory $templateCollectionFactory
    ) {
        $this->groupFactory = $groupFactory;
        $this->groupCollectionFactory = $groupCollectionFactory;
        $this->elementFactory = $elementFactory;
        $this->elementCollectionFactory = $elementCollectionFactory;
        $this->templateFieldsFactory = $templateFieldsFactory;
        $this->templateFieldsCollectionFactory = $templateFieldsCollectionFactory;
        $this->templateFactory = $templateFactory;
        $this->templateCollectionFactory = $templateCollectionFactory;
        parent::__construct($context);
    }

    /**
     * Get a group by group_code as id field
     *
     * @return Group
     */
    public function getGroup($id = false, $field = 'group_code')
    {
        $group = $this->groupFactory->create();
        if ($id) {
            $group->load($id, $field);
        }
        return $group;
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
        $elements = $this->getElementCollection()->addFieldToFilter('group_id', ['eq' => $group->getId()]);
        $group->setData('elements', $elements);
        return $group;
    }
    
    /**
     * @return Array
     */
    public function getFieldsTemplate($idGroup)
    {
        //Get template Id from group Id
        $group = $this->groupFactory->create();
        $group->load($idGroup);
        
        $templateFields = $this->getTemplateFieldsCollection()->addFieldToFilter('template_id', ['eq' => $group->getTemplateId()]);
        $templateFields->setOrder('sort_order', 'ASC');
        
        $templateData = array();
        foreach ($templateFields->getData() as $fields) {
            $templateData[$fields['label']] = $fields['type']; 
        }
 
        return $templateData;
    }
    
    public function getTemplates()
    {
        $template = $this->getTemplateCollection();
        $template->setOrder('sort_order', 'ASC');
        
        $data = array();
        foreach ($template->getData() as $temp) {
            $data[$temp['entity_id']] = $temp['code']; 
        }
 
        return $data;
    }
}
