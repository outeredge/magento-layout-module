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
     * @param type $fkType
     * @return type
     */
    public function loadByGroupAndType($fkGroup, $fkType = false)
    {
        $result = $this->getCollection();
        $result->addFilter('fk_group', $fkGroup);
        if ($fkType) {
            $result->addFilter('fk_type', $fkType);
        }
        $result->join($this->getResource()->getTable('layout_types'),
            'fk_type = id_type', 'title as typeTitle');
        $result->setOrder('sort_order');
        return $result;
    }
}
