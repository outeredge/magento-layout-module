<?php

namespace OuterEdge\Layout\Model;

use Magento\Framework\Model\AbstractModel;

class Group extends AbstractModel
{

    protected function _construct()
    {
        $this->_init('OuterEdge\Layout\Model\Resource\Group');
    }

    /**
     *
     * @param type $title
     * @return type
     */
    public function getGroupIdByCode($title = false)
    {
        $result = $this->getCollection();
        $result->addFilter('group_code', $title);
        return $result->getFirstItem();
    }
}
