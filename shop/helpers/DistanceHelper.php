<?php

namespace shop\helpers;

class DistanceHelper
{
    public static function format($distance)
    {
        return $distance / 1000 . ' км';
    }

    public static function list(): array
    {
        return [
            '10000' => 'расстояние <= 10 км',
            '30000' => 'расстояние <= 30 км',
            '60000' => 'расстояние <= 60 км',
            '120000' => 'расстояние <= 120 км',
            '180000' => 'расстояние <= 180 км',
            '240000' => 'расстояние <= 240 км',
        ];
    }
}
