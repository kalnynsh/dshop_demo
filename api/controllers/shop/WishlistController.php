<?php

namespace api\controllers\shop;

use yii\web\BadRequestHttpException;
use yii\rest\Controller;
use shop\services\cabinet\WishlistService;
use shop\readModels\Shop\ProductReadRepository;
use api\serializers\ProductSerializer;
use api\providers\MapDataProvider;

/**
 * WishlistController class
 *
 * @property ProductSerializer $serializer
 * @property WishlistService $service
 * @property ProductReadRepository $products
 */
class WishlistController extends Controller
{
    private $service;
    private $products;
    private $yiiApp;
    private $serializer;

    public function __construct(
        $id,
        $module,
        ProductSerializer $serializer,
        WishlistService $service,
        ProductReadRepository $products,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->serializer = $serializer;
        $this->service = $service;
        $this->products = $products;
        $this->yiiApp = \Yii::$app;
    }

    public function verbs(): array
    {
        return [
            'index' => ['GET'],
            'add' => ['POST'],
            'delete' => ['DELETE'],
        ];
    }

    public function actionIndex(): array
    {
        $userId = $this->getUserId();
        $dataProvider = $this->products->getWishlist($userId);

        return new MapDataProvider(
            $dataProvider,
            [$this->serialize, 'serializeWishList']
        );
    }

    public function actionAdd($id): void
    {
        try {
            $this->service->add($this->getUserId(), $id);
            $this->yiiApp->getResponse()->setStatusCode(201);
        } catch (\DomainException $err) {
            throw new BadRequestHttpException($err->getMessage(), null, $err);
        }
    }

    public function actionDelete($id): void
    {
        try {
            $this->service->remove($this->getUserId(), $id);
            $this->yiiApp->getResponse()->setStatusCode(204);
        } catch (\DomainException $err) {
            throw new BadRequestHttpException($err->getMessage(), null, $err);
        }
    }

    private function getUserId(): int
    {
        return $this->yiiApp->user->id;
    }
}
