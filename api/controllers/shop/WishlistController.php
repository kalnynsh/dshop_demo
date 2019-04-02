<?php

namespace api\controllers\shop;

use yii\web\BadRequestHttpException;
use yii\rest\Controller;
use shop\services\cabinet\WishlistService;
use shop\readModels\Shop\ProductReadRepository;
use api\serializers\ProductSerializer;
use api\providers\MapDataProvider;
use api\helpers\HttpStatusCode;
use OpenApi\Annotations as OA;

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
    private $serializers;

    public function __construct(
        $id,
        $module,
        ProductSerializer $serializers,
        WishlistService $service,
        ProductReadRepository $products,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->serializers = $serializers;
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

    public function actionIndex()
    {
        $userId = $this->getUserId();

        if (!$this->service->haveWishlistItems($userId)) {
            $this->yiiApp->getResponse()->setStatus(HttpStatusCode::OK);

            return [
                'result' => 1,
                'message' => 'User do not have any product in wishlist.',
            ];
        }

        $dataProvider = $this->products->getWishlist($userId);

        return new MapDataProvider(
            $dataProvider,
            [$this->serializers, 'serializeWishList']
        );
    }

    public function actionAdd($id): array
    {
        try {
            $this->service->add($this->getUserId(), $id);
            $this->yiiApp->getResponse()->setStatusCode(HttpStatusCode::CREATED);

            return [
                'result' => 1,
                'message' => 'The product successfully added to wishlist.',
            ];
        } catch (\DomainException $err) {
            throw new BadRequestHttpException($err->getMessage(), null, $err);
        }
    }

    public function actionDelete($id): array
    {
        try {
            $this->service->remove($this->getUserId(), $id);
            $this->yiiApp->getResponse()->setStatusCode(HttpStatusCode::NO_CONTENT);

            return [
                'result' => 1,
                'message' => 'The product successfully was removed from wishlist.',
            ];
        } catch (\DomainException $err) {
            throw new BadRequestHttpException($err->getMessage(), null, $err);
        }
    }

    private function getUserId(): int
    {
        return $this->yiiApp->user->id;
    }
}
