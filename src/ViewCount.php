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

namespace doublesecretagency\viewcount;

use yii\base\Event;

use Craft;
use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Fields;
use craft\web\twig\variables\CraftVariable;

use doublesecretagency\viewcount\fields\TotalViews;
use doublesecretagency\viewcount\models\Settings;
use doublesecretagency\viewcount\services\ViewCountService;
use doublesecretagency\viewcount\services\Query;
use doublesecretagency\viewcount\services\View;
use doublesecretagency\viewcount\variables\ViewCountVariable;

/**
 * Class ViewCount
 * @since 1.0.0
 */
class ViewCount extends Plugin
{

    /** @var Plugin $plugin Self-referential plugin property. */
    public static $plugin;

    /** @var bool $hasCpSettings The plugin has a settings page. */
    public $hasCpSettings = true;

    /** @var bool $schemaVersion Current schema version of the plugin. */
    public $schemaVersion = '1.0.0';

    /** @inheritDoc */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        // Load plugin components
        $this->setComponents([
            'viewCount' => ViewCountService::class,
            'query' => Query::class,
            'view' => View::class,
        ]);

        // Load anonymous history
        $this->viewCount->getAnonymousHistory();

        // If logged-in, load user history
        if (Craft::$app->user->getIdentity()) {
            $this->viewCount->getUserHistory();
        }

        // Register field types
        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = TotalViews::class;
            }
        );

        // Register variables
        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                $variable = $event->sender;
                $variable->set('viewCount', ViewCountVariable::class);
            }
        );

    }

    /**
     * @return Settings Plugin settings model.
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @return string The fully rendered settings template.
     */
    protected function settingsHtml(): string
    {
        $view = Craft::$app->getView();
        $overrideKeys = array_keys(Craft::$app->getConfig()->getConfigFromFile('view-count'));
        return $view->renderTemplate('view-count/settings', [
            'settings' => $this->getSettings(),
            'overrideKeys' => $overrideKeys,
            'docsUrl' => $this->documentationUrl,
        ]);
    }

}
