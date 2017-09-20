<?php

namespace OuterEdge\Layout\Model\ResourceModel\Element;

use Magento\Eav\Model\Entity\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('OuterEdge\Layout\Model\Element', 'OuterEdge\Layout\Model\ResourceModel\Element');
    }
}
