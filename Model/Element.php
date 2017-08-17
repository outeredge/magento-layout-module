<?php

namespace OuterEdge\Layout\Model;

use Magento\Framework\Model\AbstractModel;

class Element extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('OuterEdge\Layout\Model\Resource\Element');
    }

    /**
     *
     * @param type $fkGroup
     * @return type
     */
    public function loadByGroup($fkGroup)
    {
        $result = $this->getCollection();
        $result->addFilter('group_id', $fkGroup);
        $result->setOrder('sort_order');
        return $result;
    }
}
