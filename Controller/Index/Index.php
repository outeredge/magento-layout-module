<?php

namespace OuterEdge\Layout\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use OuterEdge\Layout\Model\Elements;

class Index extends Action
{
    /**
     * @var OuterEdge\Layout\Model\ElementsFactory
     */
    protected $_modelElementsFactory;

    /**
     * @param Context $context
     * @param ElementsFactory $_modelElementsFactory
     */
    public function __construct(
        Context $context,
        ElementsFactory $modelElementsFactory
    ) {
        parent::__construct($context);
        $this->_modelElementsFactory = $modelElementsFactory;
    }

    public function execute()
    {
        /**
         * When Magento get your model, it will generate a Factory class
         * for your model at var/generaton folder and we can get your
         * model by this way
         */
        $elementsModel = $this->_modelElementsFactory->create();

        // Load the item with ID is 1
        $element = $elementsModel->load(1);
        if($element){
            echo $element->getName() . " found with Id 1";
            echo "<hr/>";
        }

        // Get element collection
        $elementsCollection = $elementsModel->getCollection();
        // Load all data of collection
        foreach($elementsCollection as $element){
            echo $element->getName() . " , " . $element->getTitle() . "<br/>";
        }
    }
}