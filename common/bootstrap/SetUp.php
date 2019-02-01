<?php

namespace common\bootstrap;

use yii\mail\MailerInterface;
use yii\di\Instance;
use yii\caching\Cache;
use yii\base\BootstrapInterface;
use shop\services\ContactService;
use shop\cart\storage\CookieStorage;
use shop\cart\cost\calculator\SimpleCost;
use shop\cart\cost\calculator\DynamicCost;
use shop\cart\Cart;
use yii\rbac\ManagerInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $container = \Yii::$container;

        $container->setSingleton(
            Cache::class,
            function () use ($app) {
                return $app->cache;
            }
        );

        $container->setSingleton(
            MailerInterface::class,
            function () use ($app) {
                return $app->mailer;
            }
        );

        $container->setSingleton(
            ManagerInterface::class,
            function () use ($app) {
                return $app->authManager;
            }
        );

        $container->setSingleton(
            ContactService::class,
            [],
            [
                $app->params['adminEmail'],
                Instance::of(MailerInterface::class),
            ]
        );

        $container->setSingleton(Cart::class, function () {
            return new Cart(
                new CookieStorage('cart', 3600),
                new DynamicCost(new SimpleCost())
            );
        });
    }
}
