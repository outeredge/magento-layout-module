<?php

namespace OuterEdge\Layout\Model\ResourceModel\TemplateFields;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

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
        $this->_init('OuterEdge\Layout\Model\TemplateFields', 'OuterEdge\Layout\Model\ResourceModel\TemplateFields');
    }
}
