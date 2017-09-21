<?php

namespace OuterEdge\Layout\Helper\Templates\Adapter;

class Showroom
{   
    public function mappingFields()
    {
        return [
                'image' => 'text',
                'address' => 'text',
                'open_hours' => 'text',
                'telephone'  => 'text',
                'latitude' => 'text',
                'longitude' => 'text'
                ];       
    }

}