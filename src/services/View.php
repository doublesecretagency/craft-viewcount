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

use yii\web\Cookie;

use Craft;
use craft\base\Component;
use craft\helpers\Json;

use doublesecretagency\viewcount\ViewCount;
use doublesecretagency\viewcount\events\ViewEvent;
use doublesecretagency\viewcount\records\ElementTotal;
use doublesecretagency\viewcount\records\ViewLog;
use doublesecretagency\viewcount\records\UserHistory;

/**
 * Class View
 * @since 1.0.0
 */
class View extends Component
{

    /** @event ViewEvent The event that is triggered before a view. */
    const EVENT_BEFORE_VIEW = 'beforeView';

    /** @event ViewEvent The event that is triggered after a view. */
    const EVENT_AFTER_VIEW = 'afterView';

    //
    public function increment($elementId, $key = null, $userId = null)
    {
        // Ensure the user ID is valid
        ViewCount::$plugin->viewCount->validateUserId($userId);

        // Get request
        $request = Craft::$app->getRequest();

        // Get data
        $data = [
            'elementId' => (int) $elementId,
            'key'       => $key,
            'userId'    => ($userId ? (int) $userId : null),
            'ipAddress' => $request->getUserIP(),
            'userAgent' => $request->getUserAgent(),
        ];

        // Trigger event before a view
        if (!$this->beforeView($data)) {
            // Bail if necessary
            return [
                'success' => false,
                'error' => 'View prevented by custom event.',
                'data' => $data,
            ];
        }

        // Update element total
        $totalSuccess = $this->_updateElementTotals($elementId, $key);
        $logSuccess   = $this->_updateViewLog($elementId, $key, $userId);

        // Update user histories
        $this->_updateUserHistoryDatabase($elementId, $key, $userId);
        $this->_updateUserHistoryCookie($elementId, $key);

        // Trigger event after a view
        $this->afterView($data);

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
     * Trigger event before a view
     */
    public function beforeView($data): bool
    {
        $event = new ViewEvent($data);
        if ($this->hasEventHandlers(self::EVENT_BEFORE_VIEW)) {
            $this->trigger(self::EVENT_BEFORE_VIEW, $event);
            return $event->isValid;
        }
        return true;
    }

    /**
     * Trigger event after a view
     */
    public function afterView($data)
    {
        $event = new ViewEvent($data);
        if ($this->hasEventHandlers(self::EVENT_AFTER_VIEW)) {
            $this->trigger(self::EVENT_AFTER_VIEW, $event);
        }
    }

    // ========================================================================= //

    //
    private function _updateUserHistoryDatabase($elementId, $key, $userId)
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

        // Increment view count
        $history[$item]++;

        // Save history record
        $record->history = $history;

        // Save
        return $record->save();
    }

    //
    private function _updateUserHistoryCookie($elementId, $key)
    {
        // Get item key
        $item = ViewCount::$plugin->viewCount->setItemKey($elementId, $key);

        // Get anonymous history
        $history =& ViewCount::$plugin->viewCount->anonymousHistory;

        // If item has never been viewed, initialize key
        if (!isset($history[$item])) {
            $history[$item] = 0;
        }

        // Increment view count
        $history[$item]++;

        // Save
        $this->saveUserHistoryCookie();
    }

    //
    public function saveUserHistoryCookie()
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

    //
    private function _updateElementTotals($elementId, $key)
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

        // Update view count
        $record->viewTotal++;

        // Save
        return $record->save();
    }

    //
    private function _updateViewLog($elementId, $key, $userId)
    {
        // If not keeping a view log, bail
        if (!ViewCount::$plugin->getSettings()->keepViewLog) {
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

}
