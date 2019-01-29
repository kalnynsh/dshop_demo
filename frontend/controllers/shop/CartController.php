<?php

namespace frontend\controllers\shop;

use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use shop\services\Shop\CartService;
use shop\readModels\Shop\ProductReadRepository;
use shop\forms\Shop\AddToCartForm;

/**
 * CartController class
 *
 * @property string $layout
 * @property ProductReadRepository $products
 * @property CartService $service
 */
class CartController extends Controller
{
    public $layout = 'blank';

    private $products;
    private $service;
    private $yiiApp;

    public function __construct(
        $id,
        $module,
        CartService $service,
        ProductReadRepository $products,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->products = $products;
        $this->yiiApp = \Yii::$app;
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'quantity' => ['POST'],
                    'remove' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * actionIndex function
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'cart' => $this->service,
        ]);
    }

    /**
     * actionAdd function
     *
     * @param int|string $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionAdd($id)
    {
        if (!$product = $this->products->find($id)) {
            throw new NotFoundHttpException('The requested page dosn`t exist.');
        }

        $successAddMessage = 'Вы добавили данный товар в Вашу корзину.';
        $cartForm = $this->getAddForm($product);
        $isFormLoaded = $cartForm->load($this->yiiApp->request->post());

        if (!$product->modifications) {
            if ($isFormLoaded && $cartForm->validate()) {
                $quantity = $cartForm->quantity;
                $quantity = $this->checkQuantitySign($quantity);
            } elseif (!$isFormLoaded) {
                $quantity = $cartForm->quantity;
            }

            try {
                $this->service->add($product->id, null, $quantity);
                $this->setFlash('success', $successAddMessage);

                return $this->redirect($this->yiiApp->request->referrer);
            } catch (\DomainException $error) {
                $this->setLogErrorFlash($error);
            }
        }

        $this->layout = 'blank';

        if ($isFormLoaded && $cartForm->validate()) {
            try {
                $this->service->add(
                    $product->id,
                    $cartForm->modification,
                    $this->checkQuantitySign($cartForm->quantity)
                );
                $this->setFlash('success', $successAddMessage);

                return $this->redirect(['index']);
            } catch (\DomainException $error) {
                $this->setLogErrorFlash($error);
            }
        }

        return $this->render('add', [
            'product' => $product,
            'cartForm' => $cartForm,
        ]);
    }

    /**
     * actionQuantity function
     *
     * @param int|string $id
     * @return mixed
     */
    public function actionQuantity($id)
    {
        try {
            $this->service->set(
                $id,
                (int)$this->yiiApp->request->post('quantity')
            );
        } catch (\DomainException $error) {
            $this->setLogErrorFlash($error);
        }

        return $this->redirect(['index']);
    }

    /**
     * actionRemove function
     *
     * @param int|string $id
     * @return mixed
     */
    public function actionRemove($id)
    {
        try {
            $this->service->remove($id);
        } catch (\DomainException $error) {
            $this->setLogErrorFlash($error);
        }

        return $this->redirect(['index']);
    }

    private function setLogErrorFlash($error)
    {
        $this->yiiApp->errorHandler->logException($error);
        $this->setFlash('error', $error->getMessage());
    }

    private function setFlash($type, $message)
    {
        $this->yiiApp->session->setFlash($type, $message);
    }

    private function getAddForm($product)
    {
        return new AddToCartForm($product);
    }

    private function checkQuantitySign($quantity): int
    {
        return $quantity = (int)$quantity > 0 ? $quantity : 1;
    }
}
