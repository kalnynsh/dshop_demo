<?php

namespace common\bootstrap;

use yii\rbac\ManagerInterface;
use yii\mail\MailerInterface;
use yii\di\Instance;
use yii\caching\Cache;
use yii\base\BootstrapInterface;
use shop\services\yandex\YandexMarket;
use shop\services\yandex\ShopInfo;
use shop\services\newsletter\MailNewsletter;
use shop\services\ContactService;
use shop\cart\storage\CombineStorage;
use shop\cart\cost\calculator\SimpleCost;
use shop\cart\cost\calculator\DynamicCost;
use shop\cart\Cart;
use DrewM\MailChimp\MailChimp;

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

        $container->setSingleton(Cart::class, function () use ($app) {
            return new Cart(
                new CombineStorage(
                    $app->get('user'),
                    'cart',
                    3600 * 24,
                    $app->get('db')
                ),
                new DynamicCost(new SimpleCost())
            );
        });

        $container->setSingleton(YandexMarket::class, [], [
            new ShopInfo(
                $app->name,
                $app->name,
                $app->params['frontendHostInfo']
            ),
        ]);

        $container->setSingleton(
            MailNewsletter::class,
            function () use ($app) {
                return new MailNewsletter(
                    new MailChimp($app->params['mailChimpKey']),
                    $app->params['mailChimpListId']
                );
            }
        );
    }
}
