<?php

namespace OuterEdge\Layout\Model\Resource\Types;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'OuterEdge\Layout\Model\Types',
            'OuterEdge\Layout\Model\Resource\Types'
        );
    }
}
