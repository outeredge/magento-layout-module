<?php

namespace OuterEdge\Layout\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use OuterEdge\Layout\Model\ElementFactory;
use OuterEdge\Layout\Model\GroupFactory;

class Data extends AbstractHelper
{
    /**
     * @var ElementFactory
     */
    protected $_modelElementFactory;

    /**
     * @var GroupFactory
     */
    protected $_modelGroupFactory;

    /**
     *
     * @param ElementFactory $modelElementFactory
     * @param GroupFactory $modelGroupFactory
     */
    public function __construct(
        ElementFactory $modelElementFactory,
        GroupFactory $modelGroupFactory
    ) {
        $this->_modelElementFactory = $modelElementFactory;
        $this->_modelGroupFactory   = $modelGroupFactory;
    }

    /**
     * @param string $groupCode
     * @return array
     */
    public function getLayoutContents($groupCode = false)
    {
        $groupModel = $this->_modelGroupFactory->create();

        $groupData = $groupModel->getGroupIdByCode($groupCode);
        if (!$groupData) {
            return null;
        }

        $elementModel = $this->_modelElementFactory->create();
        $result = $elementModel->loadByGroup($groupData->getGroupId());

        $data = $groupData->getData();
        $data['element'] = $this->groupBy($result->getData(), 'title');

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
