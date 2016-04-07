<?php
class Edge_Layout_Block_Elements extends Mage_Core_Block_Template
{
    public function getLayout()
    {
        return Mage::getModel('layout/layout_elements')
            ->getCollection()
            ->setOrder('sort_order', 'ASC');
    }
}