<?php

namespace api\controllers\shop;

use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\rest\Controller;
use shop\services\Shop\CartService;
use shop\readModels\Shop\ProductReadRepository;
use shop\forms\Shop\AddToCartForm;
use api\serializers\CartSerializer;
use api\helpers\HttpStatusCode;

/**
 * CartController class serving cart
 *
 * @property ProductReadRepository $products
 * @property CartService $service
 * @property CartSerializer $serializers
 * @property Application $yiiApp
 */
class CartController extends Controller
{
    private $products;
    private $service;
    private $serializers;
    private $yiiApp;

    public function __construct(
        $id,
        $module,
        ProductReadRepository $products,
        CartService $service,
        CartSerializer $serializers,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->products = $products;
        $this->service = $service;
        $this->serializers = $serializers;
        $this->yiiApp = \Yii::$app;
    }

    public function verbs(): array
    {
        return [
            'index' => ['GET'],
            'add' => ['POST'],
            'quantity' => ['PUT'],
            'delete' => ['DELETE'],
            'clear' => ['DELETE'],
        ];
    }

    /**
     * @SWG\Get(
     *     path="/shop/cart",
     *     tags={"Cart"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/Cart"),
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     *
     * @return array
     */
    public function actionIndex(): array
    {
        $cart = $this->service->getCart();
        $cost = $cart->getCost();

        $cartArray = array_map([$this->serializers, 'serializeCartItem'], [$cart]);
        $costArray = array_map([$this->serializers, 'serializeCartCost'], [$cost]);

        $result = array_merge($cartArray, $costArray);

        return $result;
    }

    /**
     * @SWG\Post(
     *     path="/shop/products/{productId}/cart",
     *     tags={"Cart"},
     *     @SWG\Parameter(name="productId", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="modification", in="formData", required=false, type="integer"),
     *     @SWG\Parameter(name="quantity", in="formData", required=true, type="integer"),
     *     @SWG\Response(
     *         response=201,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     *
     * @param $id
     * @return array|AddToCartForm
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionAdd($id)
    {
        if (!$product = $this->products->find($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $form = $this->getAddToCartForm($product);
        $form->load($this->yiiApp->request->getBodyParams(), '');

        if ($form->validate()) {
            try {
                $this->service->add($product->id, $form->modification, $form->quantity);
                $this->yiiApp->getResponse()->setStatusCode(HttpStatusCode::CREATED);

                return [];
            } catch (\DomainException $err) {
                throw new BadRequestHttpException($err->getMessage(), null, $err);
            }
        }

        return $form;
    }

    /**
     * @SWG\Put(
     *     path="/shop/cart/{id}/quantity",
     *     tags={"Cart"},
     *     @SWG\Parameter(name="id", in="path", required=true, type="string"),
     *     @SWG\Parameter(name="quantity", in="formData", required=true, type="integer"),
     *     @SWG\Response(
     *         response=201,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     *
     * @param int $id
     * @return array
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionQuantity($id): void
    {
        try {
            $this->service->set($id, (int)$this->yiiApp->request->post('quantity'));
        } catch (\DomainException $err) {
            throw new BadRequestHttpException($err->getMessage(), null, $err);
        }
    }

    /**
     * @SWG\Delete(
     *     path="/shop/cart/{id}",
     *     tags={"Cart"},
     *     @SWG\Parameter(name="id", in="path", required=true, type="string"),
     *     @SWG\Response(
     *         response=204,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     *
     * @param int $id
     * @return array
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionDelete($id): void
    {
        try {
            $this->service->remove($id);
            $this->yiiApp->getResponse()->setStatusCode(HttpStatusCode::NO_CONTENT);
        } catch (\DomainException $err) {
            throw new BadRequestHttpException($err->getMessage(), null, $err);
        }
    }

    /**
     * @SWG\Delete(
     *     path="/shop/cart",
     *     tags={"Cart"},
     *     @SWG\Response(
     *         response=204,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     *
     * @return array
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionClear(): void
    {
        try {
            $this->service->clear();
            $this->yiiApp->getResponse()->setStatusCode(HttpStatusCode::NO_CONTENT);
        } catch (\DomainException $err) {
            throw new BadRequestHttpException($err->getMessage(), null, $err);
        }
    }

    private function getAddToCartForm($product): AddToCartForm
    {
        return new AddToCartForm($product);
    }
}

/**
 * @SWG\Definition(
 *     definition="Cart",
 *     type="object",
 *     @SWG\Property(property="weight", type="integer"),
 *     @SWG\Property(property="amount", type="integer"),
 *     @SWG\Property(property="items", type="array", @SWG\Items(
 *         type="object",
 *         @SWG\Property(property="id", type="string"),
 *         @SWG\Property(property="quantity", type="integer"),
 *         @SWG\Property(property="price", type="integer"),
 *         @SWG\Property(property="cost", type="integer"),
 *         @SWG\Property(property="product", type="object",
 *             @SWG\Property(property="id", type="integer"),
 *             @SWG\Property(property="code", type="string"),
 *             @SWG\Property(property="name", type="string"),
 *             @SWG\Property(property="thumbnail", type="string"),
 *             @SWG\Property(property="_links", type="object",
 *                 @SWG\Property(property="self", type="object", @SWG\Property(property="href", type="string")),
 *             )
 *         ),
 *         @SWG\Property(property="modification", type="object",
 *             @SWG\Property(property="id", type="integer"),
 *             @SWG\Property(property="code", type="string"),
 *             @SWG\Property(property="name", type="string"),
 *             @SWG\Property(property="_links", type="object",
 *                 @SWG\Property(property="quantity", type="object", @SWG\Property(property="href", type="string")),
 *             )
 *         )
 *     )),
 *     @SWG\Property(property="cost", type="object",
 *         @SWG\Property(property="origin", type="integer"),
 *         @SWG\Property(property="discounts", type="array", @SWG\Items(
 *             type="object",
 *             @SWG\Property(property="name", type="string"),
 *             @SWG\Property(property="value", type="integer")
 *         )),
 *         @SWG\Property(property="total", type="integer"),
 *     ),
 *     @SWG\Property(property="_links", type="object",
 *         @SWG\Property(property="self", type="object", @SWG\Property(property="href", type="string")),
 *     )
 * )
 */
