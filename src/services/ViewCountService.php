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

    /**
     * @var string Name of cookie containing the user history.
     */
    public string $userCookie = 'ViewHistory';

    /**
     * @var int Length of time until user history cookie expires.
     */
    public int $userCookieLifespan = 315569260; // Lasts 10 years

    /**
     * @var array History of anonymous user.
     */
    public array $anonymousHistory = [];

    /**
     * @var array History of logged-in User.
     */
    public array $loggedInHistory = [];

    /**
     * Generate combined item key.
     *
     * @param int $elementId
     * @param null|string $key
     * @return string
     */
    public function setItemKey(int $elementId, ?string $key): string
    {
        return $elementId.($key ? ':'.$key : '');
    }

    /**
     * Load history of logged-in user.
     */
    public function getUserHistory(): void
    {
        // If table has not been created yet, bail
        if (!Craft::$app->getDb()->tableExists('{{%viewcount_userhistories}}')) {
            return;
        }

        // Get current user
        $currentUser = Craft::$app->user->getIdentity();

        // If no current user, bail
        if (!$currentUser) {
            return;
        }

        // Get history of current user
        $this->loggedInHistory = ViewCount::$plugin->query->userHistory($currentUser->id);
    }

    /**
     * Load history of anonymous user.
     */
    public function getAnonymousHistory(): void
    {
        // Get request
        $request = Craft::$app->getRequest();

        // If running via command line, bail
        if ($request->getIsConsoleRequest()) {
            return;
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

    /**
     * Whether a key is valid.
     *
     * @param null|string $key
     * @return bool
     */
    public function validKey(?string $key): bool
    {
        return (null === $key || is_string($key) || is_numeric($key));
    }

    // ========================================================================= //

    /**
     * Ensures we are working with a valid User ID.
     * Will convert a User model into a User ID.
     *
     * @param null|int|User $userId
     */
    public function validateUserId(null|int|User &$userId): void
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
