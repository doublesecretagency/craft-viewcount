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

namespace doublesecretagency\viewcount\web\assets;

use craft\web\AssetBundle;

/**
 * Class FieldInputAssets
 * @since 1.0.0
 */
class FieldInputAssets extends AssetBundle
{

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();

        $this->sourcePath = '@doublesecretagency/viewcount/resources';

        $this->css = [
            'css/totalviews-input.css',
        ];
    }

}
