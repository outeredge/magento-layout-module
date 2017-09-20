<?php

namespace OuterEdge\Layout\Model\ResourceModel\GroupTemplate;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'template_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('OuterEdge\Layout\Model\GroupTemplate', 'OuterEdge\Layout\Model\ResourceModel\GroupTemplate');
    }
}
