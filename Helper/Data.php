<?php

namespace OuterEdge\Layout\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use OuterEdge\Layout\Model\ElementsFactory;
use OuterEdge\Layout\Model\GroupsFactory;

class Data extends AbstractHelper
{
    /**
     * @var ElementsFactory
     */
    protected $_modelElementsFactory;

    /**
     * @var GroupsFactory
     */
    protected $_modelGroupsFactory;

    /**
     *
     * @param ElementsFactory $modelElementsFactory
     * @param GroupsFactory $modelGroupsFactory
     */
    public function __construct(
        ElementsFactory $modelElementsFactory,
        GroupsFactory $modelGroupsFactory
    ) {
        $this->_modelElementsFactory = $modelElementsFactory;
        $this->_modelGroupsFactory   = $modelGroupsFactory;
    }

    /**
     * @param string $groupCode
     * @return array
     */
    public function getLayoutContents($groupCode = false)
    {
        $groupsModel = $this->_modelGroupsFactory->create();

        $groupData = $groupsModel->getGroupIdByCode($groupCode);
        if (!$groupData) {
            return null;
        }

        $elementsModel = $this->_modelElementsFactory->create();
        $result = $elementsModel->loadByGroup($groupData->getGroupId());

        $data = $groupData->getData();
        $data['elements'] = $this->groupBy($result->getData(), 'title');

        return $data;
    }

    /**
     * @param array $array
     * @param string $key
     * @return array
     */
    protected function groupBy($array, $key)
    {
        $return = [];
        foreach ($array as $val) {
            $return[$val[$key]] = $val;
        }
        return $return;
    }
}
