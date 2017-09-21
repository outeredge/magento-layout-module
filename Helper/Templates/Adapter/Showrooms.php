<?php

namespace OuterEdge\Layout\Helper\Templates\Adapter;

class Showrooms
{   
    public function mappingFields()
    {
        return [
                'link' => 'text',
                'link_text' => 'text',
                'image' => 'image',
                'address' => 'text',
                'open_hours' => 'text',
                'telephone'  => 'text',
                'latitude' => 'text',
                'longitude' => 'text'
                ];       
    }

}