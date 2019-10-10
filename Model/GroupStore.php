<?php

namespace OuterEdge\Layout\Model;

use Magento\Framework\Model\AbstractModel;

class GroupStore extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('OuterEdge\Layout\Model\ResourceModel\GroupStore');
    }
}
