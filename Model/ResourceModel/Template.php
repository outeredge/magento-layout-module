<?php

namespace OuterEdge\Layout\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Template extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('layout_template', 'entity_id');
    }
}
