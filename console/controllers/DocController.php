<?php

namespace console\controllers;

use yii\console\Controller;
use Yii;

class DocController extends Controller
{
    public function actionBuild(): void
    {
        $openapi = Yii::getAlias('@vendor/bin/openapi');
        $source = Yii::getAlias('@api');
        $target = Yii::getAlias('@api/web/docs/api.json');
        $bootstrap = Yii::getAlias('@api/config/params-local.php');
        $format = 'json';

        $command = '"'
            . PHP_BINARY
            . '"'
            . " \"{$openapi}\" "
            . "--output \"{$target}\" "
            . "--bootstrap \"{$bootstrap}\" "
            . "--format \"{$format}\" "
            . " \"{$source}\"; ";

        \passthru($command);
    }
}
