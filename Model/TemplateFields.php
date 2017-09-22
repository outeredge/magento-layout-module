<?php

namespace OuterEdge\Layout\Model;

use Magento\Framework\Model\AbstractModel;

class TemplateFields extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('OuterEdge\Layout\Model\ResourceModel\TemplateFields');
    }
}
