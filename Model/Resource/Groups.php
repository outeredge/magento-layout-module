<?php

namespace OuterEdge\Layout\Model\Resource;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Groups extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('layout_groups', 'id_group');
    }
}
