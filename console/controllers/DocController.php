<?php

namespace console\controllers;

use yii\console\Controller;
use Yii;

class DocController extends Controller
{
    public function actionBuild(): void
    {
        $swagger = Yii::getAlias('@vendor/zircote/swagger-php/bin/swagger');
        $source = Yii::getAlias('@api');
        $target = Yii::getAlias('@api/web/docs/api.json');
        $bootstrap = Yii::getAlias('@api/config/params-local.php');

        $command = '"'
            . PHP_BINARY
            . '"'
            . " \"{$swagger}\" "
            . "--output \"{$target}\" "
            . "--bootstrap \"{$bootstrap}\" "
            . " \"{$source}\"; ";

        \passthru($command);
    }
}
