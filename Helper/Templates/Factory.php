<?php

namespace OuterEdge\Layout\Helper\Templates;

class Factory
{
    public function getAdapter($name)
    {
        $className = "OuterEdge\Layout\Helper\Templates\Adapter\\" . ucfirst($name);
        if (class_exists($className)) {
            return new $className();
        } else {
            throw new \InvalidArgumentException("The class $className doesn't exist");
        }
    }
    
}