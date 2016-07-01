<?php

namespace OuterEdge\Layout\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use OuterEdge\Layout\Model\ElementsFactory;
use OuterEdge\Layout\Model\GroupsFactory;
use OuterEdge\Layout\Model\TypesFactory;

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
     * @var OuterEdge\Layout\Model\TypesFactory
     */
    protected $_modelTypesFactory;

    /**
     *
     * @param ElementsFactory $modelElementsFactory
     * @param GroupsFactory $modelGroupsFactory
     */
    public function __construct(
        ElementsFactory $modelElementsFactory,
        GroupsFactory $modelGroupsFactory,
        TypesFactory $modelTypesFactory)
    {
        $this->_modelElementsFactory = $modelElementsFactory;
        $this->_modelGroupsFactory   = $modelGroupsFactory;
        $this->_modelTypesFactory     = $modelTypesFactory;
    }

    /**
     * getLayoutContents
     * @param type $groupTitle
     * @param type $type
     * @return type
     */
    public function getLayoutContents($groupTitle = false, $type = false)
    {
        /**
        * @var OuterEdge\Layout\Model\GroupsFactory
        */
        $groupsModel = $this->_modelGroupsFactory->create();
        $idGroup     = $groupsModel->getGroupIdByName($groupTitle);

        if (!$idGroup) {
            return null;
        }

        /**
         * @var OuterEdge\Layout\Model\TypeFactory
         */
        $typesModel = $this->_modelTypesFactory->create();
        $fkType     = $typesModel->getTypeIdByName($type);

        /**
         * @var OuterEdge\Layout\Model\ElementsFactory
         */
        $elementsModel = $this->_modelElementsFactory->create();
        $result        = $elementsModel->loadByGroupAndType($idGroup->getIdGroup(), $fkType->getIdType());

        return $this->groupBy($result->getData(), 'typeTitle');
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
            $return[$val[$key]][] = $val;
        }
        return $return;
    }
}
