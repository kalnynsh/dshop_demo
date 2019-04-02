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
use OpenApi\Annotations as OA;

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
            'add' => ['GET'],
            'quantity' => ['PUT'],
            'delete' => ['DELETE'],
            'clear' => ['DELETE'],
        ];
    }

    /**
     * @OA\Get(
     *    path="/shop/cart",
     *    summary="List all item of cart",
     *    operationId="actionIndex",
     *    tags={"Cart"},
     *    @OA\Response(
     *        response=200,
     *        description="Success response",
     *        @OA\Schema(
     *            type="array",
     *            @OA\Items(ref="#/components/schemas/Cart")
     *       )
     *   ),
     *     @OA\Response(
     *         response="default",
     *         description="Unexpected error",
     *         @OA\Schema(ref="#/components/schemas/ErrorModel")
     *     ),
     *   security={
     *       {"Bearer": {}, "OAuth2": {}}
     *   }
     * )
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
     * @OA\Post(
     *    path="/shop/products/{productId}/cart",
     *    summary="Add product to cart",
     *    operationId="actionAdd",
     *    tags={"Cart"},
     *    @OA\Parameter(
     *        name="productId",
     *        in="path",
     *        required=true,
     *        description="The id of the product to be added",
     *        @OA\Schema(
     *          type="integer"
     *        )
     *    ),
     *    @OA\Parameter(
     *        name="modification",
     *        in="formData",
     *        required=true,
     *        description="The id of modification to be added",
     *        @OA\Schema(
     *          type="integer"
     *        )
     *    ),
     *     @OA\RequestBody(
     *        description="Modification data",
     *        required=false,
     *        @OA\JsonContent(ref="#/components/schemas/ProductModification")
     *    ),
     *    @OA\Response(
     *        response=201,
     *        description="Success response",
     *        @OA\JsonContent(ref="#/components/schemas/ResultResponse")
     *   ),
     *   @OA\Response(
     *       response="default",
     *       description="Unexpected error",
     *       @OA\JsonContent(ref="#/components/schemas/ErrorModel")
     *   ),
     *   security={
     *      {"Bearer": {}, "OAuth2": {}}
     *    }
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

                return [
                    'result' => 1,
                    'message' => 'The product successfully added to cart.',
                ];
            } catch (\DomainException $err) {
                throw new BadRequestHttpException($err->getMessage(), null, $err);
            }
        }

        return $form;
    }

    /**
     * @OA\Put(
     *  path="/shop/cart/{id}/quantity",
     *  tags={"Cart"},
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      description="The id of the product to be changed quantity",
     *      @OA\Schema(
     *        type="integer"
     *      )
     *    ),
     *  @OA\RequestBody(
     *      description="Quantity",
     *      required=true,
     *      @OA\JsonContent(ref="#/components/schemas/Quantity"),
     *  ),
     *  @OA\Response(
     *      response=201,
     *      description="Success response",
     *      @OA\JsonContent(ref="#/components/schemas/ResultResponse")
     *   ),
     *   @OA\Response(
     *       response="default",
     *       description="Unexpected error",
     *       @OA\JsonContent(ref="#/components/schemas/ErrorModel")
     *     ),
     *   security={
     *       {"Bearer": {}, "OAuth2": {}}
     *   }
     * )
     *
     * @param int $id
     * @return array
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionQuantity($id): array
    {
        try {
            $this->service->set($id, (int)$this->yiiApp->request->post('quantity'));

            return [
                    'result' => 1,
                    'message' => 'The quantity of cart item successfully was changed.',
            ];
        } catch (\DomainException $err) {
            throw new BadRequestHttpException($err->getMessage(), null, $err);
        }
    }

    /**
     * @OA\Delete(
     *  path="/shop/cart/{id}",
     *  tags={"Cart"},
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      description="The id of the product to be deleted",
     *      @OA\Schema(
     *        type="integer"
     *      )
     *    ),
     *  @OA\Response(
     *      response=204,
     *      description="Success response",
     *      @OA\JsonContent(ref="#/components/schemas/ResultResponse")
     *   ),
     *     @OA\Response(
     *         response="default",
     *         description="Unexpected error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModel")
     *     ),
     *   security={
     *       {"Bearer": {}, "OAuth2": {}}
     *   }
     * )
     *
     * @param int $id
     * @return array
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionDelete($id): array
    {
        try {
            $this->service->remove($id);
            $this->yiiApp->getResponse()->setStatusCode(HttpStatusCode::NO_CONTENT);

            return [
                'result' => 1,
                'message' => 'The product successfully was removed from cart.',
            ];
        } catch (\DomainException $err) {
            throw new BadRequestHttpException($err->getMessage(), null, $err);
        }
    }

    /**
     * @OA\Delete(
     *  path="/shop/cart",
     *  tags={"Cart"},
     *  @OA\Response(
     *      response=204,
     *      description="Success response",
     *      @OA\JsonContent(ref="#/components/schemas/ResultResponse")
     *   ),
     *   @OA\Response(
     *       response="default",
     *       description="Unexpected error",
     *       @OA\JsonContent(ref="#/components/schemas/ErrorModel")
     *   ),
     *   security={
     *       {"Bearer": {}, "OAuth2": {}}
     *   }
     * )
     *
     * @param int $id
     * @return array
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionClear(): array
    {
        try {
            $this->service->clear();
            $this->yiiApp->getResponse()->setStatusCode(HttpStatusCode::NO_CONTENT);

            return [
                'result' => 1,
                'message' => 'All products successfully were removed from cart.',
            ];
        } catch (\DomainException $err) {
            throw new BadRequestHttpException($err->getMessage(), null, $err);
        }
    }

    private function getAddToCartForm($product): AddToCartForm
    {
        return new AddToCartForm($product);
    }
}
