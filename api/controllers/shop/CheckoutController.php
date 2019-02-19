<?php

namespace api\controllers\shop;

use yii\web\BadRequestHttpException;
use yii\rest\Controller;
use yii\helpers\Url;
use shop\services\Shop\OrderService;
use shop\forms\Shop\Order\OrderForm;
use Yii;

/**
 * CheckoutController class serving cart checkout
 *
 * @property OrderService $service
 * @property Application $yiiApp
 */
class CheckoutController extends Controller
{
    private $service;
    private $yiiApp;

    public function __construct(
        $id,
        $module,
        OrderService $service,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->yiiApp = \Yii::$app;
    }

    public function verbs(): array
    {
        return [
            'index' => ['POST'],
        ];
    }

    public function actionIndex(): array
    {
        $form = $this->getOrderForm();

        $form->load($this->yiiApp->request->getBodyParams(), '');

        if ($form->validate()) {
            try {
                $order = $this->service->checkout($this->yiiApp->user->id, $form);
                $response = $this->yiiApp->getResponse();
                $response->setStatusCode(201);

                $response->getHeaders()->set(
                    'Location',
                    Url::to(['shop/order/view', 'id' => $order->id], true)
                );

                return [];
            } catch (\DomainException $err) {
                throw new BadRequestHttpException($err->getMessage(), null, $err);
            }
        }

        return $form;
    }

    private function getOrderForm(): OrderForm
    {
        return new OrderForm();
    }
}
