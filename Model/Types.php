<?php

namespace OuterEdge\Layout\Model;

use Magento\Framework\Model\AbstractModel;

class Types extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('OuterEdge\Layout\Model\Resource\Types');
    }

    /**
     *
     * @param type $title
     * @return type
     */
    public function getTypeIdByName($title = false)
    {
        $result = $this->getCollection();
        $result->addFilter('title', $title);
        return $result->getFirstItem();
    }
}
