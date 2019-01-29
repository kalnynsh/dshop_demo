<?php

namespace frontend\controllers\shop;

use yii\web\Controller;
use yii\filters\AccessControl;
use shop\services\Shop\OrderService;
use shop\forms\Shop\Order\OrderForm;
use shop\cart\Cart;

/**
 * CheckoutController class
 *
 * @property string $layout
 * @property OrderService $service
 * @property Cart $cart
 */
class CheckoutController extends Controller
{
    public $layout = 'blank';

    private $yiiApp;
    private $service;
    private $cart;

    public function __construct(
        $id,
        $module,
        OrderService $service,
        Cart $cart,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->cart = $cart;
        $this->yiiApp = \Yii::$app;
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
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
        $form = $this->getOrderForm();

        if ($form->load($this->yiiApp->request->post())
            && $form->validate()
        ) {
            try {
                $order = $this->service->checkout($this->yiiApp->user->id, $form);

                return $this->redirect(['/cabinet/order/view', 'id' => $order->id]);
            } catch (\DomainException $error) {
                $this->setLogErrorFlash($error);
            }
        }

        return $this->render('index', [
            'cart' => $this->cart,
            'model' => $form,
        ]);
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

    private function getOrderForm()
    {
        return new OrderForm();
    }
}
