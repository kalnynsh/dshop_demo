<?php

namespace shop\helpers;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use shop\entities\Shop\Order\Status;

class OrderHelper
{
    public static function statusesList(): array
    {
        return [
            Status::NEW => 'Новый',
            Status::PAID => 'Оплаченный',
            Status::SENT => 'Отправлен',
            Status::COMPLETED => 'Отменен',
            Status::CANCELLED_BY_CUSTOMER => 'Отменен покупателем',
            Status::PAYMENT_PENDING => 'Ожидается оплата',
            Status::PAYMENT_FAIL => 'Ошибка при оплате',
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusesList(), $status);
    }

    public static function statusLabel($status): string
    {
        switch ($status) {
            case Status::NEW:
                $class = 'label label-primary';
                break;
            case Status::PAID:
                $class = 'label label-success';
                break;
            case Status::SENT:
                $class = 'label label-info';
                break;
            case Status::COMPLETED:
                $class = 'label label-default';
                break;
            case Status::CANCELLED:
                $class = 'label label-warning';
                break;
            default:
                $class = 'label label-danger';
        }

        return Html::tag(
            'span',
            ArrayHelper::getValue(self::statusesList(), $status),
            [
                'class' => $class,
            ]
        );
    }

    public static function statusString($status): string
    {
        return ArrayHelper::getValue(self::statusesList(), $status);
    }
}
