<?php

namespace OuterEdge\Layout\Model;

use Magento\Framework\Model\AbstractModel;

class Group extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('OuterEdge\Layout\Model\ResourceModel\Group');
    }
}
