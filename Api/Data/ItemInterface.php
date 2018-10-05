<?php

namespace OuterEdge\Layout\Api\Data;

/**
 * Interface ItemInterface
 * @api
 */
interface ItemInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ENTITY_ID         = 'entity_id';
    const TITLE             = 'title';
    const SORT_ORDER        = 'sort_order';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();
   
    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Get sort order
     *
     * @return int|null
     */
    public function getSortOrder();

    /**
     * Set ID
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Set title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * Set sort order
     *
     * @param int $sortOrder
     * @return $this
     */
    public function setSortOrder($sortOrder);
}
