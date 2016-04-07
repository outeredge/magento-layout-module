<?php
class Edge_Layout_Model_Resource_Layout_Elements_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('layout/layout_elements');
    }
}