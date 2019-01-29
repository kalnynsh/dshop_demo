<?php

namespace shop\helpers;

use yii\helpers\ArrayHelper;
use shop\entities\Shop\Brand;

class BrandsHelper
{
    public static function list(): array
    {
        return ArrayHelper::map(
            Brand::find()
                ->orderBy('name')
                ->asArray()
                ->all(),
            'id',
            'name'
        );
    }
}
