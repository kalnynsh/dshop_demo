<?php

namespace console\controllers;

use yii\console\Controller;

class QueueTestController extends Controller
{
    public function actionRun()
    {
        $message = 'Test message ' . \date('Y-m-d H:i:s') . PHP_EOL;

        \Yii::$app->queue->push(new TestJob([
            'name' => $message
        ]));
    }
}
