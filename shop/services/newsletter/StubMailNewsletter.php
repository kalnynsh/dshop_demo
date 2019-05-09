<?php

namespace shop\services\newsletter;

use shop\services\newsletter\interfaces\Inewsletter;

class StubMailNewsletter implements Inewsletter
{
    private $logger;

    public function __construct($logger)
    {
        $this->logger = $logger;
    }

    public function subscribe($email): void
    {
        \Yii::info('Email address ' . $email . ' subscribed');

        $this->logger->log(
            'Email address '
            . $email
            . ' subscribed',
            Logger::LEVEL_INFO
        );
    }

    public function unsubscribe($email): void
    {
        $this->logger->log(
            'Email address '
            . $email
            . ' unsubscribed',
            Logger::LEVEL_INFO
        );
    }
}
