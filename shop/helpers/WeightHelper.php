<?php

namespace shop\helpers;

class WeightHelper
{
    public static function format($weight): string
    {
        if ($weight < 1000) {
            return $weight . ' гр';
        }

        if ($weight < 1000000) {
            return $weight / 1000 . ' кг';
        }

        return $weight / 1000000 . ' т';
    }
}
