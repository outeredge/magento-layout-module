<?php

namespace OuterEdge\Layout\Plugin;

use Magento\MediaStorage\Model\File\Uploader;

class CheckAllowedMediaStorage
{
    public function afterCheckAllowedExtension(Uploader $subject, $extension)
    {
        if ('svg' == $extension) {
            return true;
        }
    }
}
