<?php
class Edge_Layout_Model_Resource_Layout_Groups extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('layout/layout_groups', 'id_group');
    }
}