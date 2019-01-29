<?php

namespace frontend\bootstrap;

use yii\widgets\Breadcrumbs;
use yii\helpers\ArrayHelper;
use yii\base\BootstrapInterface;
use Yii;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $container = Yii::$container;

        $container->set(Breadcrumbs::class, function ($container, $params, $args) {
            return new Breadcrumbs(ArrayHelper::merge([
                'homeLink' => [
                    'label' => '<i class="fa fa-home"></i>',
                    'encode' => false,
                    'url' => Yii::$app->homeUrl,
                ],
            ], $args));
        });
    }
}
