<?php

namespace shop\services\sms;

use yii\log\Logger;

class StubSmsSender implements IsmsSender
{
    private $logger;

    public function __construct($logger)
    {
        $this->logger = $logger;
    }

    public function send($number, $text): void
    {
        $this->logger->log(
            'Message for '
            . $number
            . ': '
            . $text,
            Logger::LEVEL_INFO
        );
    }
}
