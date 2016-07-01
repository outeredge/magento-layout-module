<?php

namespace OuterEdge\Layout\Model\Resource;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Types extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('layout_types', 'id_type');
    }
}
