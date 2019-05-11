<?php

namespace frontend\controllers;

use shop\entities\Shop\Product\Product;
use shop\services\yandex\YandexMarket;
use yii\caching\TagDependency;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

class MarketController extends Controller
{
    private $market;
    private $yiiApp;

    public function __construct(
        $id,
        $module,
        YandexMarket $market,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->market = $market;
        $this->yiiApp = \Yii::$app;
    }

    public function actionIndex(): Response
    {
        $xml = $this->yiiApp->cache->getOrSet('yandex-market', function () {
            return $this->market->generate(function (Product $product) {
                return Url::to([
                    '/shop/catalog/product',
                    'id' => $product->id,
                ], true);
            });
        },
            null,
            new TagDependency([
                'tags' => [
                    'categories',
                    'products',
                ],
            ])
        );

        return $this->yiiApp->response->sendContentAsFile(
            $xml,
            'yandex-market.xml',
            [
                'mimeType' => 'application/xml',
                'inline'   => true,
            ]
        );
    }
}
