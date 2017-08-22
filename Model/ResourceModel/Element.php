<?php

namespace OuterEdge\Layout\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Element extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('layout_element', 'element_id');
    }
}
