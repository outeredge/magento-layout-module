<?php

namespace OuterEdge\Layout\Model;

use Magento\Framework\Model\AbstractModel;

class Element extends AbstractModel
{
    const ENTITY = 'layout_element';
   
    protected function _construct()
    {
        $this->_init('OuterEdge\Layout\Model\ResourceModel\Element');
    }

}
