<?php
/**
 * View Count plugin for Craft CMS
 *
 * Count the number of times an element has been viewed.
 *
 * @author    Double Secret Agency
 * @link      https://www.doublesecretagency.com/
 * @copyright Copyright (c) 2019 Double Secret Agency
 */

namespace doublesecretagency\viewcount\variables;

use craft\elements\db\ElementQuery;
use doublesecretagency\viewcount\ViewCount;

/**
 * Class ViewCountVariable
 * @since 1.0.0
 */
class ViewCountVariable
{

    /**
     * Output total views of element.
     *
     * @param int $elementId
     * @param null|string $key
     * @return int
     */
    public function total(int $elementId, ?string $key = null): int
    {
        return ViewCount::$plugin->query->total($elementId, $key);
    }

    // ========================================================================= //

    /**
     * Increment view count.
     *
     * @param int $elementId
     * @param null|string $key
     * @param null|int $userId
     */
    public function increment(int $elementId, ?string $key = null, ?int $userId = null): void
    {
        ViewCount::$plugin->view->increment($elementId, $key, $userId);
    }

    /**
     * Decrement view count.
     *
     * @param int $elementId
     * @param null|string $key
     * @param null|int $userId
     */
    public function decrement(int $elementId, ?string $key = null, ?int $userId = null): void
    {
        ViewCount::$plugin->view->decrement($elementId, $key, $userId);
    }

    // ========================================================================= //

    /**
     * Set a counter to the specified integer.
     *
     * @param int $newValue
     * @param int $elementId
     * @param null|string $key
     * @return bool Whether setting the counter was successful.
     */
    public function setCounter(int $newValue, int $elementId, ?string $key = null): bool
    {
        return ViewCount::$plugin->view->setCounter($newValue, $elementId, $key);
    }

    /**
     * Reset a counter to zero.
     *
     * @param int $elementId
     * @param null|string $key
     * @return bool Whether resetting the counter was successful.
     */
    public function resetCounter(int $elementId, ?string $key = null): bool
    {
        return ViewCount::$plugin->view->resetCounter($elementId, $key);
    }

    // ========================================================================= //

    /**
     * Sort by "most viewed".
     *
     * @param ElementQuery $elements
     * @param null|string $key
     */
    public function sort(ElementQuery $elements, ?string $key = null): void
    {
        ViewCount::$plugin->query->orderByViews($elements, $key);
    }

}
