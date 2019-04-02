<?php

namespace api\controllers\shop;

use yii\web\BadRequestHttpException;
use yii\rest\Controller;
use shop\services\cabinet\WishlistService;
use shop\readModels\Shop\ProductReadRepository;
use api\serializers\ProductSerializer;
use api\providers\MapDataProvider;
use api\helpers\HttpStatusCode;

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

    /**
     * @SWG\Get(
     *     path="/shop/wishlist",
     *     tags={"WishList"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/WishlistItem")
     *         ),
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     */
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

    /**
     * @SWG\Post(
     *     path="/shop/products/{productId}/wish",
     *     tags={"WishList"},
     *     @SWG\Parameter(name="productId", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=201,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     *
     * @param $id
     * @throws BadRequestHttpException
     */
    public function actionAdd($id): void
    {
        try {
            $this->service->add($this->getUserId(), $id);
            $this->yiiApp->getResponse()->setStatusCode(HttpStatusCode::CREATED);
        } catch (\DomainException $err) {
            throw new BadRequestHttpException($err->getMessage(), null, $err);
        }
    }

    /**
     * @SWG\Delete(
     *     path="/shop/wishlist/{id}",
     *     tags={"WishList"},
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=204,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     * @param $id
     * @throws BadRequestHttpException
     */
    public function actionDelete($id): void
    {
        try {
            $this->service->remove($this->getUserId(), $id);
            $this->yiiApp->getResponse()->setStatusCode(HttpStatusCode::NO_CONTENT);
        } catch (\DomainException $err) {
            throw new BadRequestHttpException($err->getMessage(), null, $err);
        }
    }

    private function getUserId(): int
    {
        return $this->yiiApp->user->id;
    }
}

/**
 * @SWG\Definition(
 *     definition="WishlistItem",
 *     type="object",
 *     @SWG\Property(property="id", type="integer"),
 *     @SWG\Property(property="code", type="string"),
 *     @SWG\Property(property="name", type="string"),
 *     @SWG\Property(property="price", type="object",
 *         @SWG\Property(property="new", type="integer"),
 *         @SWG\Property(property="old", type="integer"),
 *     ),
 *     @SWG\Property(property="thumbnail", type="string"),
 *     @SWG\Property(property="_links", type="object",
 *         @SWG\Property(property="self", type="object", @SWG\Property(property="href", type="string")),
 *         @SWG\Property(property="cart", type="object", @SWG\Property(property="href", type="string")),
 *     ),
 * )
 */
