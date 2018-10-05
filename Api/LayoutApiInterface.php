<?php

namespace OuterEdge\Layout\Api;

use OuterEdge\Layout\Api\Data\ItemInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;

/**
 * Menu item CRUD interface
 *
 * @api
 */
interface LayoutApiInterface
{
    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return \OuterEdge\Layout\Api\Data\ItemSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
