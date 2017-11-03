<?php

namespace OuterEdge\Layout\Block\Adminhtml\Element\Helper;

use Magento\Framework\Data\Form\Element\Image as ElementImage;

class Image extends ElementImage
{
    const LAYOUT_IMAGE_DIR = 'layout';

    /**
     * Get image preview url
     *
     * @return string
     */
    protected function _getUrl()
    {
        return self::LAYOUT_IMAGE_DIR . $this->getValue();
    }
}
