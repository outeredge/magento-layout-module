<?php

namespace OuterEdge\Layout\Model;

use OuterEdge\Layout\Api\Data\ItemInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Cms\Helper\Page;
use Magento\Framework\Registry;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;

class LayoutApiItem extends AbstractModel implements ItemInterface
{
    /**
     * @param Context $context
     * @param ProductFactory $productFactory
     * @param CategoryFactory $categoryFactory
     * @param Page $pageHelper
     * @param Registry $registry
     * @param AbstractResource $resource
     * @param AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        ProductFactory $productFactory,
        CategoryFactory $categoryFactory,
        Page $pageHelper,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->productFactory = $productFactory;
        $this->categoryFactory = $categoryFactory;
        $this->pageHelper = $pageHelper;
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
    }

    protected function _construct()
    {
        $this->_init('OuterEdge\Layout\Model\ResourceModel\Group');
    }

    /**
     * Get item ID
     *
     * @return int
     */
    public function getId()
    {
        return parent::getData(self::ENTITY_ID);
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * Get sort order
     *
     * @return int
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }
    
    /**
     * Set ID
     *
     * @param int $id
     * @return ItemInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }

    /**
     * Set title
     *
     * @param string $title
     * @return ItemInterface
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * Set sort order
     *
     * @param int $sortOrder
     * @return ItemInterface
     */
    public function setSortOrder($sortOrder)
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }   
}
