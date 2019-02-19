<?php

namespace api\controllers\shop;

use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\rest\Controller;
use shop\services\Shop\CartService;
use shop\readModels\Shop\ProductReadRepository;
use shop\forms\Shop\AddToCartForm;
use api\serializers\CartSerializer;
use Yii;

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
            'quantity' => ['POST'],
            'delete' => ['DELETE'],
            'clear' => ['DELETE'],
        ];
    }

    public function actionIndex(): array
    {
        $cart = $this->service->getCart();
        $cost = $cart->getCost();

        $cartArray = array_map([$this->serializers, 'serializeCartItem'], [$cart]);
        $costArray = array_map([$this->serializers, 'serializeCartCost'], [$cost]);

        $result = array_merge($cartArray, $costArray);

        return $result;
    }

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
                $this->yiiApp->getResponse()->setStatusCode(201);

                return [];
            } catch (\DomainException $err) {
                throw new BadRequestHttpException($err->getMessage(), null, $err);
            }
        }

        return $form;
    }

    public function actionQuantity($id): void
    {
        try {
            $this->service->set($id, (int)$this->yiiApp->request->post('quantity'));
        } catch (\DomainException $err) {
            throw new BadRequestHttpException($err->getMessage(), null, $err);
        }
    }

    public function actionDelete($id): void
    {
        try {
            $this->service->remove($id);
            $this->yiiApp->getResponse()->setStatusCode(204);
        } catch (\DomainException $err) {
            throw new BadRequestHttpException($err->getMessage(), null, $err);
        }
    }

    public function actionClear(): void
    {
        try {
            $this->service->clear();
            $this->yiiApp->getResponse()->setStatusCode(204);
        } catch (\DomainException $err) {
            throw new BadRequestHttpException($err->getMessage(), null, $err);
        }
    }

    private function getAddToCartForm($product): AddToCartForm
    {
        return new AddToCartForm($product);
    }
}
