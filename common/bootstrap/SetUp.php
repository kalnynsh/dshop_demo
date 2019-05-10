<?php

namespace common\bootstrap;

use shop\cart\Cart;
use shop\cart\cost\calculator\DynamicCost;
use shop\cart\cost\calculator\SimpleCost;
use shop\cart\storage\CombineStorage;
use shop\dispatchers\DeferredEventDispatcher;
use shop\dispatchers\IEventDispatcher;
use shop\dispatchers\SimpleEventDispatcher;
use shop\entities\Shop\Product\events\ProductAppearedInStock;
use shop\entities\User\events\UserSignUpConfirmed;
use shop\entities\User\events\UserSignUpRequested;
use shop\jobs\AsyncEventJobHandler;
// use shop\services\sms\SmsRu;
// use shop\services\sms\LoggedSmsSender;
// use shop\services\newsletter\MailNewsletter;
use shop\listeners\Shop\Product\ProductAppearedInStockListener;
use shop\listeners\User\UserSignupConfirmedListener;
use shop\listeners\User\UserSignupRequestedListener;
use shop\services\ContactService;
use shop\services\newsletter\StubMailNewsletter;
use shop\services\sms\StubSmsSender;
use shop\services\yandex\ShopInfo;
use shop\services\yandex\YandexMarket;
use yii\base\BootstrapInterface;
use yii\caching\Cache;
use yii\console\ErrorHandler;
use yii\di\Container;
use yii\di\Instance;
use yii\mail\MailerInterface;
use yii\queue\Queue;
use yii\rbac\ManagerInterface;

// use DrewM\MailChimp\MailChimp;

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

        $container->setSingleton(ErrorHandler::class, function () use ($app) {
            return $app->errorHandler;
        });

        $container->setSingleton(Queue::class, function () use ($app) {
            return $app->get('queue');
        });

        $container->setSingleton(Cache::class, function () use ($app) {
            return $app->cache;
        });

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

        // $container->setSingleton(
        //     MailNewsletter::class,
        //     function () use ($app) {
        //         return new MailNewsletter(
        //             new MailChimp($app->params['mailChimpKey']),
        //             $app->params['mailChimpListId']
        //         );
        //     }
        // );

        $container->setSingleton(
            Inewsletter::class,
            function () use ($app) {
                return new StubMailNewsletter(
                    \Yii::getLogger()
                );
            }
        );

        // $container->setSingleton(
        //     LoggedSmsSender::class,
        //     function () use ($app) {
        //         return new LoggedSmsSender(
        //             new SmsRu($app->params['smsRuKey']),
        //             \Yii::getLogger()
        //         );
        //     }
        // );

        $container->setSingleton(
            IsmsSender::class,
            function () use ($app) {
                return new StubSmsSender(
                    \Yii::getLogger()
                );
            }
        );

        $container->setSingleton(
            IEventDispatcher::class,
            DeferredEventDispatcher::class
        );

        $container->setSingleton(
            DeferredEventDispatcher::class,
            function (Container $container) {
                return new DeferredEventDispatcher(
                    new AsyncEventDispatcher(
                        $container->get(Queue::class)
                    )
                );
            }
        );

        $container->setSingleton(
            SimpleEventDispatcher::class,
            function (Container $container) {
                return new SimpleEventDispatcher($container, [
                    UserSignUpRequested::class    => [UserSignupRequestedListener::class],
                    UserSignUpConfirmed::class    => [UserSignUpConfirmedListener::class],
                    ProductAppearedInStock::class => [ProductAppearedInStockListener::class],
                ]);
            }
        );

        $container->setSingleton(AsyncEventJobHandler::class, [], [
            Instance::of(SimpleEventDispatcher::class),
        ]);
    }
}
