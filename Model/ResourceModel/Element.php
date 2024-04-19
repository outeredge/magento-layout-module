<?php

namespace OuterEdge\Layout\Model\ResourceModel;

use Magento\Eav\Model\Entity\AbstractEntity;

class Element extends AbstractEntity
{
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
        
        return parent::getEntityType();
    }
}
