<?php

namespace OuterEdge\Layout\Model;

use Magento\Framework\Model\AbstractModel;

class Elements extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('OuterEdge\Layout\Model\Resource\Elements');
    }

    /**
     *
     * @param type $fkGroup
     * @return type
     */
    public function loadByGroup($fkGroup)
    {
        $result = $this->getCollection();
        $result->addFilter('fk_group', $fkGroup);
        $result->setOrder('sort_order');
        return $result;
    }
}
