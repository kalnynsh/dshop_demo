<?php

namespace shop\helpers;

use yii\helpers\ArrayHelper;
use shop\entities\Shop\Category;

class CategoriesHelper
{
    public static function list(): array
    {
        return ArrayHelper::map(
            Category::find()
                ->andWhere(['>', 'depth', 0])
                ->orderBy('lft')
                ->asArray()
                ->all(),
            'id',
            function (array $category) {
                return ($category['depth'] > 1
                    ? str_repeat('-- ', $category['depth'] - 1) . ' '
                    : '')
                    . $category['name'];
            }
        );
    }
}
