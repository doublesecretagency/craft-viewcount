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

namespace doublesecretagency\viewcount\services;

use Craft;
use craft\base\Component;
use craft\helpers\Json;
use doublesecretagency\viewcount\ViewCount;
use doublesecretagency\viewcount\events\ViewEvent;
use doublesecretagency\viewcount\records\ElementTotal;
use doublesecretagency\viewcount\records\UserHistory;
use doublesecretagency\viewcount\records\ViewLog;
use yii\web\Cookie;

/**
 * Class View
 * @since 1.0.0
 */
class View extends Component
{

    /**
     * @event ViewEvent The event that is triggered before a view.
     */
    const EVENT_BEFORE_VIEW = 'beforeView';

    /**
     * @event ViewEvent The event that is triggered after a view.
     */
    const EVENT_AFTER_VIEW = 'afterView';

    /**
     * Increment the view counter for a given Element.
     *
     * @param int $elementId
     * @param null|string $key
     * @param null|int $userId
     * @param bool $decrement
     * @return array
     */
    public function increment(int $elementId, ?string $key = null, ?int $userId = null, bool $decrement = false): array
    {
        // Ensure the user ID is valid
        ViewCount::$plugin->viewCount->validateUserId($userId);

        // Get request
        $request = Craft::$app->getRequest();

        // Get data
        $data = [
            'elementId' => $elementId,
            'key'       => $key,
            'userId'    => ($userId ?: null),
            'ipAddress' => $request->getUserIP(),
            'userAgent' => $request->getUserAgent(),
        ];

        // If not decrementing the counter
        if (!$decrement) {
            // Trigger event before a view
            if (!$this->beforeView($data)) {
                // Bail if necessary
                return [
                    'success' => false,
                    'error' => 'View prevented by custom event.',
                    'data' => $data,
                ];
            }
        }

        // Update element total
        $totalSuccess = $this->_updateElementTotals($elementId, $key, $decrement);
        $logSuccess = $this->_updateViewLog($elementId, $key, $userId, $decrement);

        // Update user histories
        $this->_updateUserHistoryDatabase($elementId, $key, $userId, $decrement);
        $this->_updateUserHistoryCookie($elementId, $key, $decrement);

        // If not decrementing the counter
        if (!$decrement) {
            // Trigger event after a view
            $this->afterView($data);
        }

        // Basic error check
        if (!$totalSuccess) {
            $error = 'Unable to update element total.';
        } else if (!$logSuccess) {
            $error = 'Unable to log details.';
        } else {
            $error = null;
        }

        // Return data
        return [
            'success' => ($totalSuccess && $logSuccess),
            'error' => $error,
            'data' => $data,
        ];
    }

    /**
     * Decrement the view counter for a given Element.
     *
     * @param int $elementId
     * @param null|string $key
     * @param null|int $userId
     * @return array
     */
    public function decrement(int $elementId, ?string $key = null, ?int $userId = null): array
    {
        // Increment, but with the decrement flag
        return $this->increment($elementId, $key, $userId, true);
    }

    // ========================================================================= //

    /**
     * Trigger event before a view.
     *
     * @param array $data
     * @return bool
     */
    public function beforeView(array $data): bool
    {
        $event = new ViewEvent($data);
        if ($this->hasEventHandlers(self::EVENT_BEFORE_VIEW)) {
            $this->trigger(self::EVENT_BEFORE_VIEW, $event);
            return $event->isValid;
        }
        return true;
    }

    /**
     * Trigger event after a view.
     *
     * @param array $data
     */
    public function afterView(array $data): void
    {
        $event = new ViewEvent($data);
        if ($this->hasEventHandlers(self::EVENT_AFTER_VIEW)) {
            $this->trigger(self::EVENT_AFTER_VIEW, $event);
        }
    }

    // ========================================================================= //

    /**
     * Update view history for the User in the database.
     *
     * @param int $elementId
     * @param null|string $key
     * @param null|int $userId
     * @param bool $decrement
     * @return bool Whether a record was saved.
     */
    private function _updateUserHistoryDatabase(int $elementId, ?string $key, ?int $userId, bool $decrement): bool
    {
        // If user is not logged in, return false
        if (!$userId) {
            return false;
        }

        // Get item key
        $item = ViewCount::$plugin->viewCount->setItemKey($elementId, $key);

        // Load existing element history
        $record = UserHistory::findOne([
            'id' => $userId,
        ]);

        // If record already exists
        if ($record) {

            // Get history from existing record
            $history = Json::decode($record->history);

        } else {

            // Create new record
            $record = new UserHistory;
            $record->id = $userId;

            // Create new history
            $history = [];

        }

        // If item has never been viewed, initialize key
        if (!isset($history[$item])) {
            $history[$item] = 0;
        }

        // Adjust value according to whether it is increment/decrement
        $history[$item] = $this->_adjustValue($history[$item], $decrement);

        // Save history record
        $record->history = $history;

        // Save
        return $record->save();
    }

    /**
     * Update view history for the User history cookie.
     *
     * @param int $elementId
     * @param null|string $key
     * @param bool $decrement
     */
    private function _updateUserHistoryCookie(int $elementId, ?string $key, bool $decrement): void
    {
        // Get item key
        $item = ViewCount::$plugin->viewCount->setItemKey($elementId, $key);

        // Get anonymous history
        $history =& ViewCount::$plugin->viewCount->anonymousHistory;

        // If item has never been viewed, initialize key
        if (!isset($history[$item])) {
            $history[$item] = 0;
        }

        // Adjust value according to whether it is increment/decrement
        $history[$item] = $this->_adjustValue($history[$item], $decrement);

        // Save
        $this->saveUserHistoryCookie();
    }

    /**
     * Save the history cookie.
     */
    public function saveUserHistoryCookie(): void
    {
        // Get cookie settings
        $cookieName = ViewCount::$plugin->viewCount->userCookie;
        $history    = ViewCount::$plugin->viewCount->anonymousHistory;
        $lifespan   = ViewCount::$plugin->viewCount->userCookieLifespan;

        // Set cookie
        $cookie = new Cookie();
        $cookie->name = $cookieName;
        $cookie->value = Json::encode($history);
        $cookie->expire = time() + $lifespan;
        Craft::$app->getResponse()->getCookies()->add($cookie);
    }

    /**
     * Update the view count total for a given Element.
     *
     * @param int $elementId
     * @param null|string $key
     * @param bool $decrement
     * @return bool
     */
    private function _updateElementTotals(int $elementId, ?string $key, bool $decrement): bool
    {
        // Load existing element totals
        $record = ElementTotal::findOne([
            'elementId' => $elementId,
            'viewKey'   => $key,
        ]);

        // If no totals record exists, create new
        if (!$record) {
            $record = new ElementTotal;
            $record->elementId = $elementId;
            $record->viewKey   = $key;
            $record->viewTotal = 0;
        }

        // Adjust value according to whether it is increment/decrement
        $record->viewTotal = $this->_adjustValue($record->viewTotal, $decrement);

        // Save
        return $record->save();
    }

    /**
     * Add a new record to the view log for a given interaction.
     *
     * @param int $elementId
     * @param null|string $key
     * @param null|int $userId
     * @param bool $decrement
     * @return bool
     */
    private function _updateViewLog(int $elementId, ?string $key, ?int $userId, bool $decrement): bool
    {
        // If not keeping a view log, bail
        if (!ViewCount::$plugin->getSettings()->keepViewLog) {
            return true;
        }

        // If decrementing the counter, bail
        if ($decrement) {
            return true;
        }

        // Get request
        $request = Craft::$app->getRequest();

        // Log view
        $record = new ViewLog;
        $record->elementId = $elementId;
        $record->viewKey   = $key;
        $record->userId    = $userId;
        $record->ipAddress = $request->getUserIP();
        $record->userAgent = $request->getUserAgent();

        // Save
        return $record->save();
    }

    /**
     * Adjust the value up or down accordingly.
     *
     * @param int $value
     * @param bool $decrement
     * @return int
     */
    public function _adjustValue(int $value, bool $decrement): int
    {
        // If decrement flag was set
        if ($decrement) {
            // Decrement view count
            $value--;
        } else {
            // Increment view count
            $value++;
        }

        // Can't be less than zero
        if ($value < 0) {
            $value = 0;
        }

        return $value;
    }


}
