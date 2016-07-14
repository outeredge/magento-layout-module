<?php

namespace OuterEdge\Layout\Model\Resource;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Elements extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('layout_elements', 'element_id');
    }
}
