<?php

namespace shop\helpers;

use shop\services\manage\access\Rbac;
use shop\entities\User\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\rbac\Item;

class UserHelper
{
    public static function statusList(): array
    {
        return [
            User::STATUS_WAIT => 'Wait',
            User::STATUS_ACTIVE => 'Active',
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusLabel($status): string
    {
        switch ($status) {
            case User::STATUS_WAIT:
                $class = 'label label-default';
                break;
            case User::STATUS_ACTIVE:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }

        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }

    public static function rolesList(): array
    {
        return ArrayHelper::map(
            \Yii::$app->authManager->getRoles(),
            'name',
            'description'
        );
    }

    public static function rolesListByUser($userId): array
    {
        return ArrayHelper::map(
            \Yii::$app->authManager->getRolesByUser($userId),
            'name',
            'description'
        );
    }

    public static function roleLabel($roleName): string
    {
        switch ($roleName) {
            case Rbac::ROLE_USER:
                $class = 'label label-primary';
                break;
            case Rbac::ROLE_ADMIN:
                $class = 'label label-danger';
                break;
            default:
                $class = 'label label-default';
        }

        return Html::tag(
            'span',
            ArrayHelper::getValue(self::rolesList(), $roleName),
            [
                'class' => $class,
            ]
        );
    }

    public static function roleLabelByUser($userId): string
    {
        $roles = \Yii::$app->authManager->getRolesByUser($userId);

        if (!empty($roles)) {
            return implode(', ', array_map(function (Item $role) {
                return self::roleLabel($role->name);
            }, $roles));
        }

        return '';
    }
}
