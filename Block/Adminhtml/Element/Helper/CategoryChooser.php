<?php

namespace OuterEdge\Layout\Block\Adminhtml\Element\Helper;

use Magento\Catalog\Block\Adminhtml\Product\Helper\Form\Category;

class CategoryChooser extends Category
{
    /**
     * {@inheritdoc}
     */
    public function getAfterElementHtml()
    {
        $htmlId = $this->getHtmlId();
        $suggestPlaceholder = __('click here to display the categories');
        $selectorOptions = $this->_jsonEncoder->encode($this->_getSelectorOptions());

        return <<<HTML
    <input id="{$htmlId}-suggest" placeholder="$suggestPlaceholder" />
    <script>
        require(["jquery", "mage/mage"], function($){
            $('#{$htmlId}-suggest').mage('treeSuggest', {$selectorOptions});
        });
    </script>
HTML;
    }
}
