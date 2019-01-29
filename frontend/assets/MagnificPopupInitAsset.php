<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class MagnificPopupInitAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [];

    public $js = [
        'js/magnificPopup/magnificPopupInit.js',
    ];

    public $depends = [
        'frontend\assets\MagnificPopupAsset',
    ];
}
