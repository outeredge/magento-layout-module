<?php

namespace OuterEdge\Layout\Model\Resource\Element;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'OuterEdge\Layout\Model\Element',
            'OuterEdge\Layout\Model\Resource\Element'
        );
    }
}
