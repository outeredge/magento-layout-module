<?php

namespace OuterEdge\Layout\Setup;

use Magento\Eav\Setup\EavSetup;

class ElementSetup extends EavSetup
{
    public function getDefaultEntities()
    {
        $elementEntity = \OuterEdge\Layout\Model\Element::ENTITY;
        $entities = [
            $elementEntity => [
                'entity_model' => 'OuterEdge\Layout\Model\ResourceModel\Element',
                'table' => $elementEntity . '_entity',
                'attributes' => [
                    'group_id' => [
                        'type' => 'static',
                    ],
                    'title' => [
                        'type' => 'static',
                    ],
                    'sort_order' => [
                        'type' => 'static',
                    ],
                ],
            ],
        ];
        return $entities;
    }
}
