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

namespace doublesecretagency\viewcount\events;

use craft\events\CancelableEvent;

/**
 * Class ViewEvent
 * @since 1.0.0
 */
class ViewEvent extends CancelableEvent
{

    /** @var int|null The element ID for the item being viewed. */
    public $elementId;

    /** @var string|null An optional key. */
    public $key;

    /** @var int|null ID of user who viewed (null if anonymous). */
    public $userId;

    /** @var string|null IP address of visitor. */
    public $ipAddress;

    /** @var string|null User agent of visitor. */
    public $userAgent;

}
