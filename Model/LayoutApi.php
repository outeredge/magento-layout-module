<?php
namespace OuterEdge\Layout\Model;
 
use OuterEdge\Layout\Api\LayoutApiInterface as ApiInterface;
use OuterEdge\Layout\Helper\Data as HelperData;
use Magento\Framework\Api\SearchCriteriaInterface;
use OuterEdge\Layout\Api\Data\ItemSearchResultsInterfaceFactory;
 
class LayoutApi implements ApiInterface {
 
    /**
     * @var HelperData $helper
     */
    protected $helper;

     /**
     * @var ItemSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;
 
    /**
     * @param HelperData $helper
     * @param ItemSearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        HelperData $helper,
        ItemSearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->helper = $helper;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * {@inheritdoc}
     *
     * @return ItemInterface[] Array of item data objects.
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->helper->getGroupCollection();

        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setTotalCount($collection->getSize());

        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());

        $searchResult->setItems($collection->getItems());
        return $searchResult;
    }
}