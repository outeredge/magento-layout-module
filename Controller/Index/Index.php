<?php

namespace OuterEdge\Layout\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use OuterEdge\Layout\Helper\Data;

class Index extends Action
{
    /**
     * @var OuterEdge\Layout\Helper\Data
     */
    protected $_helper;

    public function __construct(
        Context $context,
        Data $helper
    ) {
        parent::__construct($context);
        $this->_helper = $helper;
    }

    public function execute()
    {
        $result = $this->_helper->getLayoutContents('homepage');
        print_r($result);
    }
}