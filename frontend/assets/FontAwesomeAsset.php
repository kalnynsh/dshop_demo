<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Frontend application font-awesome asset bundle.
 */
class FontAwesomeAsset extends AssetBundle
{
    public $sourcePath = '@bower/font-awesome/';
    public $css = [
        'css/font-awesome.min.css',
    ];
}
