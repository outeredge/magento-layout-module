<?php

namespace OuterEdge\Layout\Model\Resource;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Group extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('layout_group', 'group_id');
    }
}
