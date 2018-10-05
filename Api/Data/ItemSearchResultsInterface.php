<?php

namespace OuterEdge\Layout\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * @api
 */
interface ItemSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get items
     *
     * @return \OuterEdge\Layout\Api\Data\ItemInterface[]
     */
    public function getItems();
}
