<?php

namespace OuterEdge\Layout\Model;

use Magento\Framework\Model\AbstractModel;

class Groups extends AbstractModel
{

    protected function _construct()
    {
        $this->_init('OuterEdge\Layout\Model\Resource\Groups');
    }

    /**
     *
     * @param type $title
     * @return type
     */
    public function getGroupIdByName($title = false)
    {
        $result = $this->getCollection();
        $result->addFilter('title', $title);
        return $result->getFirstItem();
    }
}
