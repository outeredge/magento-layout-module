<?php

namespace OuterEdge\Layout\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class GroupStore extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('layout_group_store', 'group_id');
    }
}
