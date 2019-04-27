<?php

namespace shop\services\sms;

interface IsmsSender
{
    public function send($number, $text): void;
}
