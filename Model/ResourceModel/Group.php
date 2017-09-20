<?php

namespace OuterEdge\Layout\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Group extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('layout_group', 'group_id');
    }
}
