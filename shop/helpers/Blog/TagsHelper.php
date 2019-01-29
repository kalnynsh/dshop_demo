<?php

namespace shop\helpers\Blog;

use yii\helpers\ArrayHelper;
use shop\entities\Blog\Tag;

class TagsHelper
{
    public function list(): array
    {
        return ArrayHelper::map(
            Tag::find()
                ->orderBy('name')
                ->asArray()
                ->all(),
            'id',
            'name'
        );
    }

    public function splitNames(string $names): array
    {
        return array_filter(
            array_map(
                'trim',
                preg_split('#\s*,\s*#i', $names)
            )
        );
    }
}
