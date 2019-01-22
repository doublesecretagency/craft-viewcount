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
use craft\elements\User;
use craft\helpers\Json;

use doublesecretagency\viewcount\ViewCount;

/**
 * Class ViewCountService
 * @since 1.0.0
 */
class ViewCountService extends Component
{

    public $userCookie = 'ViewHistory';
    public $userCookieLifespan = 315569260; // Lasts 10 years
    public $anonymousHistory = [];
    public $loggedInHistory = [];

    // Generate combined item key
    public function setItemKey($elementId, $key)
    {
        return $elementId.($key ? ':'.$key : '');
    }

    // Get history of logged-in user
    public function getUserHistory()
    {
        // If table has not been created yet, bail
        if (!Craft::$app->getDb()->tableExists('{{%viewcount_userhistories}}')) {
            return false;
        }

        // Get current user
        $currentUser = Craft::$app->user->getIdentity();

        // If no current user, bail
        if (!$currentUser) {
            return false;
        }

        // Get history of current user
        $this->loggedInHistory = ViewCount::$plugin->query->userHistory($currentUser->id);
    }

    // Get history of anonymous user
    public function getAnonymousHistory()
    {
        // Get request
        $request = Craft::$app->getRequest();

        // If running via command line, bail
        if ($request->getIsConsoleRequest()) {
            return false;
        }

        // Get cookies object
        $cookies = $request->getCookies();

        // If cookie exists
        if ($cookies->has($this->userCookie)) {

            // Get anonymous history
            $cookieValue = $cookies->getValue($this->userCookie);
            $this->anonymousHistory = Json::decode($cookieValue);

        }

        // If no anonymous history
        if (!$this->anonymousHistory) {

            // Initialize anonymous history
            $this->anonymousHistory = [];
            ViewCount::$plugin->view->saveUserHistoryCookie();

        }

    }

    // Check if a key is valid
    public function validKey($key)
    {
        return (null === $key || is_string($key) || is_numeric($key));
    }

    // ========================================================================= //

    // $userId can be valid user ID or UserModel
    public function validateUserId(&$userId)
    {
        // No user by default
        $user = null;

        // Handle user ID
        if (!$userId) {
            // Default to logged in user
            $user = Craft::$app->user->getIdentity();
        } else {
            if (is_numeric($userId)) {
                // Get valid UserModel
                $user = Craft::$app->users->getUserById($userId);
            } else if (is_object($userId) && is_a($userId, User::class)) {
                // It's already a User model
                $user = $userId;
            }
        }

        // Get user ID, or rate anonymously
        $userId = ($user ? $user->id : null);
    }

}
