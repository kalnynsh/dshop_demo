<?php

namespace console\controllers;

use yii\queue\Job;
use yii\base\BaseObject;

class TestJob extends BaseObject implements Job
{
    public $name;

    public function execute($queue)
    {
        file_put_contents(__DIR__ . '/test_message.log', $this->name);
    }
}
