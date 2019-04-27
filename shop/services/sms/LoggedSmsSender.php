<?php

namespace shop\services\sms;

use yii\log\Logger;

class LoggedSmsSender implements IsmsSender
{
    private $next;
    private $logger;

    public function __construct(IsmsSender $next, Logger $logger)
    {
        $this->next = $next;
        $this->logger = $logger;
    }

    public function send($number, $text): void
    {
        $this->next->send($number, $text);

        $this->logger->log(
            'Message to '
            . $number
            . ': '
            . $text,
            Logger::LEVEL_INFO
        );
    }
}
