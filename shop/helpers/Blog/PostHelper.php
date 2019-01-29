<?php

namespace shop\helpers\Blog;

use shop\entities\Blog\Post\Post;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class PostHelper
{
    public static function statusList(): array
    {
        return [
            Post::STATUS_DRAFT => 'Draft',
            Post::STATUS_ACTIVE => 'Active',
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusLabel($status): string
    {
        switch ($status) {
            case Post::STATUS_DRAFT:
                $class = 'label label-default';
                break;
            case Post::STATUS_ACTIVE:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }

        return Html::tag(
            'span',
            ArrayHelper::getValue(self::statusList(), $status),
            [
                'class' => $class,
            ]
        );
    }
}
