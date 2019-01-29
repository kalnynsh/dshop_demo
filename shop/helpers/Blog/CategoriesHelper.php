<?php

namespace shop\helpers\Blog;

use yii\helpers\ArrayHelper;
use shop\entities\Blog\Category;

class CategoriesHelper
{
    public static function list($param = 'name'): array
    {
        return ArrayHelper::map(
            Category::find()
                ->orderBy('sort')
                ->asArray()
                ->all(),
            'id',
            $param
        );
    }
}
