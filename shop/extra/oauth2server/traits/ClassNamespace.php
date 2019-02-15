<?php

namespace shop\extra\oauth2server\traits;

trait ClassNamespace
{
    public static function className()
    {
        return get_called_class();
    }
}
