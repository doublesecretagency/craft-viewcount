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

namespace doublesecretagency\viewcount\controllers;

use Craft;
use craft\web\Controller;

use doublesecretagency\viewcount\ViewCount;

/**
 * Class IncrementController
 * @since 1.0.0
 */
class IncrementController extends Controller
{

    // Increment view counter
    public function actionIndex()
    {
        $this->requirePostRequest();

        // Get request
        $request = Craft::$app->getRequest();

        // Get POST values
        $elementId = $request->getBodyParam('id');
        $key       = $request->getBodyParam('key');

        // Increment view counter
        $response = ViewCount::$plugin->view->increment($elementId, $key);

        // Return response
        return $this->asJson($response);
    }

}
