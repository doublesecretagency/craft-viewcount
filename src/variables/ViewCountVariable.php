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

    // Output total views of element
    public function total($elementId, $key = null)
    {
        return ViewCount::$plugin->query->total($elementId, $key);
    }

    // ========================================================================

    // Increment view count
    public function increment($elementId, $key = null, $userId = null)
    {
        ViewCount::$plugin->view->increment($elementId, $key, $userId);
    }

    // Sort by "most viewed"
    public function sort(ElementQuery $elements, $key = null)
    {
        ViewCount::$plugin->query->orderByViews($elements, $key);
    }

}
