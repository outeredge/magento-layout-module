<?php

namespace OuterEdge\Layout\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use OuterEdge\Layout\Model\ElementsFactory;
use OuterEdge\Layout\Model\GroupsFactory;

class Data extends AbstractHelper
{
    /**
     * @var OuterEdge\Layout\Model\ElementsFactory
     */
    protected $_modelElementsFactory;

    /**
     * @var OuterEdge\Layout\Model\GroupsFactory
     */
    protected $_modelGroupsFactory;

    /**
     *
     * @param ElementsFactory $modelElementsFactory
     * @param GroupsFactory $modelGroupsFactory
     */
    public function __construct(
        ElementsFactory $modelElementsFactory,
        GroupsFactory $modelGroupsFactory)
    {
        $this->_modelElementsFactory = $modelElementsFactory;
        $this->_modelGroupsFactory   = $modelGroupsFactory;
    }

    /**
     * getLayoutContents
     * @param type $groupTitle
     * @return array
     */
    public function getLayoutContents($groupCode = false)
    {
        /**
        * @var OuterEdge\Layout\Model\GroupsFactory
        */
        $groupsModel = $this->_modelGroupsFactory->create();
        $idGroup     = $groupsModel->getGroupIdByCode($groupCode);

        if (!$idGroup) {
            return null;
        }

        /**
         * @var OuterEdge\Layout\Model\ElementsFactory
         */
        $elementsModel = $this->_modelElementsFactory->create();
        $result        = $elementsModel->loadByGroup($idGroup->getGroupId());

        return $result->getData();
    }

}
