<?php
class Edge_Layout_Model_Layout_Groups extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('layout/layout_groups');
    }

    public function getGroupIdByName($name = false)
    {
        $result = $this->getCollection();
        $result->addFilter('name', $name);

        return $result->getFirstItem();
    }
}
