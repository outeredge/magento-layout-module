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
     * @param Context $context
     * @param GroupFactory $groupFactory
     * @param GroupCollectionFactory $groupCollectionFactory
     * @param ElementFactory $elementFactory
     * @param ElementCollectionFactory $elementCollectionFactory
     */
    public function __construct(
        Context $context,
        GroupFactory $groupFactory,
        GroupCollectionFactory $groupCollectionFactory,
        ElementFactory $elementFactory,
        ElementCollectionFactory $elementCollectionFactory
    ) {
        $this->groupFactory = $groupFactory;
        $this->groupCollectionFactory = $groupCollectionFactory;
        $this->elementFactory = $elementFactory;
        $this->elementCollectionFactory = $elementCollectionFactory;
        parent::__construct($context);
    }

    /**
     * @return Group
     */
    public function getGroup($id = false, $field = null)
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
     * @return Group
     */
    public function getGroupAndElements($id, $field = null)
    {
        $group = $this->getGroup($id, $field);
        $elements = $this->getElementCollection()->addFieldToFilter('group_id', ['eq' => $group->getId()]);
        $group->setData('elements', $elements);
        return $group;
    }
}
