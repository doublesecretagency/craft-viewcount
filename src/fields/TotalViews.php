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

namespace doublesecretagency\viewcount\fields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\base\PreviewableFieldInterface;

use doublesecretagency\viewcount\web\assets\FieldInputAssets;
use doublesecretagency\viewcount\ViewCount;

/**
 * Class TotalViews
 * @since 1.0.0
 */
class TotalViews extends Field implements PreviewableFieldInterface
{

    /**
     * @var string|null
     */
    public $viewKey;

    // ========================================================================= //

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        $TotalViews = Craft::t('view-count', 'Total Views');
        $ViewCount  = Craft::t('view-count', 'View Count');
        return "{$TotalViews} ({$ViewCount})";
    }

    /**
     * @inheritdoc
     */
    public static function hasContentColumn(): bool
    {
        return false;
    }

    // ========================================================================= //

    /**
     * Prep value for use as the data leaves the database.
     *
     * @inheritdoc
     */
    public function normalizeValue($value, ElementInterface $element = null)
    {
        return $this->_getTotal($element);
    }

    // ========================================================================= //

    /**
     * @inheritdoc
     */
    public function getSettingsHtml(): string
    {
        return Craft::$app->getView()->renderTemplate('view-count/fields/totalviews-settings', [
            'settings' => $this->getSettings()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        $view = Craft::$app->getView();
        $view->registerAssetBundle(FieldInputAssets::class);
        return $view->renderTemplate('view-count/fields/totalviews-input', [
            'totalViews' => $this->_getTotal($element)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getTableAttributeHtml($value, ElementInterface $element): string
    {
        return Craft::$app->getView()->renderTemplate('view-count/fields/totalviews-column', [
            'totalViews' => $this->_getTotal($element)
        ]);
    }

    // ========================================================================= //

    /**
     * Get total views of element
     *
     * @param $element
     * @return float
     */
    private function _getTotal($element): float
    {
        $viewKey = ($this->viewKey ? $this->viewKey : null);
        return ViewCount::$plugin->query->total($element->id, $viewKey);
    }

}
