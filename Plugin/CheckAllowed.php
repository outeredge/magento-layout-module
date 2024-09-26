<?php

namespace OuterEdge\Layout\Plugin;

use Magento\Framework\File\Uploader;

class CheckAllowed
{
    public function aroundCheckAllowedExtension(Uploader $subject, callable $proceed, $extension)
    {
        if ('svg' == $extension) {
            return true;
        }
        return $proceed($extension);
    }
}
