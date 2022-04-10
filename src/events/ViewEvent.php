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

    /**
     * @var null|int The element ID for the item being viewed.
     */
    public ?int $elementId;

    /**
     * @var null|string An optional key.
     */
    public ?string $key;

    /**
     * @var null|int ID of user who viewed (null if anonymous).
     */
    public ?int $userId;

    /**
     * @var null|string IP address of visitor.
     */
    public ?string $ipAddress;

    /**
     * @var null|string User agent of visitor.
     */
    public ?string $userAgent;

}
