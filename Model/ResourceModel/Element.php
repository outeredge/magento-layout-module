<?php

namespace OuterEdge\Layout\Model\ResourceModel;

use Magento\Eav\Model\Entity\AbstractEntity;

class Element extends AbstractEntity
{
    protected function _construct()
    {
        $this->_read = 'layout_element_read';
        $this->_write = 'layout_element_write';
    }
    
    /**
     * Getter and lazy loader for _type
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return \Magento\Eav\Model\Entity\Type
     */
    public function getEntityType()
    {
        if (empty($this->_type)) {
            $this->setType(\OuterEdge\Layout\Model\Element::ENTITY);
        }
        
        die('ok');
        return parent::getEntityType();
    }
}
