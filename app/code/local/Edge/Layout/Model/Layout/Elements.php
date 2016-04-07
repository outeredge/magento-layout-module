<?php
class Edge_Layout_Model_Layout_Elements extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('layout/layout_elements');
    }

    public function loadByType($fkGroup, $type = false)
    {
        $result = $this->getCollection();
        $result->addFilter('fk_group', $fkGroup);
        if ($type) {
            $result->addFilter('type', $type);
        }

        return $result;
    }
}
