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
use yii\web\BadRequestHttpException;
use yii\web\Response;

/**
 * Class DecrementController
 * @since 1.2.0
 */
class DecrementController extends Controller
{

    /**
     * @inheritdoc
     */
    protected array|bool|int $allowAnonymous = true;

    /**
     * Decrement view counter.
     *
     * @return Response
     * @throws BadRequestHttpException
     */
    public function actionIndex(): Response
    {
        $this->requirePostRequest();

        // Get request
        $request = Craft::$app->getRequest();

        // Get POST values
        $elementId = $request->getBodyParam('id');
        $key       = $request->getBodyParam('key');

        // Decrement view counter
        $response = ViewCount::$plugin->view->decrement($elementId, $key);

        // Return response
        return $this->asJson($response);
    }

}
