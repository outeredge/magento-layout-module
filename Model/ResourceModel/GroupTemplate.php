<?php

namespace OuterEdge\Layout\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class GroupTemplate extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('layout_group_template', 'template_id');
    }
}
