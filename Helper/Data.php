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
        $groupData    = $groupsModel->getGroupIdByCode($groupCode);

        if (!$groupData) {
            return null;
        }

        /**
         * @var OuterEdge\Layout\Model\ElementsFactory
         */
        $elementsModel = $this->_modelElementsFactory->create();
        $result        = $elementsModel->loadByGroup($groupData->getGroupId());

        $data = $groupData->getData();
        $data['elements'] = $this->groupBy($result->getData(), 'title');

        return $data;

    }

    /**
     * groupBy
     * @param type $array
     * @param type $key
     * @return type
     */
    protected function groupBy($array, $key)
    {
        $return = array();
        foreach($array as $val) {
            $return[$val[$key]] = $val;
        }
        return $return;
    }

}
