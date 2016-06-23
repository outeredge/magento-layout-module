<?php

namespace OuterEdge\Layout\Model\Resource\Elements;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'OuterEdge\Layout\Model\Elements',
            'OuterEdge\Layout\Model\Resource\Elements'
        );
    }
}
