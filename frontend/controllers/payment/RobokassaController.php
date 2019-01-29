<?php

namespace frontend\controllers\payment;

use yii\web\NotFoundHttpException;
use yii\web\Controller;
use shop\services\Shop\OrderService;
use shop\readModels\Shop\OrderReadRepository;
use shop\entities\Shop\Order\Order;
use robokassa\SuccessAction;
use robokassa\ResultAction;
use robokassa\Merchant;

/**
 * RobokassaController - process payments
 * via Robokassa service
 *
 * @property OrderReadRepository $orders
 * @property OrderService        $service
 */
class RobokassaController extends Controller
{
    public $enableCsfValidation = false;

    private $orders;
    private $service;
    private $method;
    private $yiiApp;

    public function __construct(
        $id,
        $module,
        OrderReadRepository $orders,
        OrderService $service,
        $config = []
    ) {
        parent::__construct($id, $module, $config);

        $this->orders = $orders;
        $this->service = $service;
        $this->yiiApp = \Yii::$app;
        $this->method = 'Robokassa';
    }

    public function actionInvoice($id)
    {
        $order = $this->findOrder($id);
        $userEmail = $order->user->email ?? null;
        $this->service->pending($order->id, $this->method);

        return $this->getMerchant()->payment(
            $order->cost,
            $order->id,
            'payment',
            'Оплата заказа',
            $userEmail,
            null
        );
    }

    public function actionInvoiceTest($id)
    {
        $order = $this->findOrder($id);
        $this->service->pending($order->id, $this->method);

        return $this->getMerchant()->payment(
            "100.00",
            678678,
            "Товары для животных"
        );
    }

    public function actions(): array
    {
        return [
            'result' => [
                'class' => ResultAction::class,
                'callback' => [$this, 'resultCallback'],
            ],
            'success' => [
                'class' => SuccessAction::class,
                'callback' => [$this, 'successCallback'],
            ],
            'fail' => [
                'class' => FailAction::class,
                'callback' => [$this, 'failCallback'],
            ],
        ];
    }

    public function resultCallback($merchant, $nInvId, $nOutSum, $shp)
    {
        /** @var Order */
        $order = $this->findOrder($nInvId);

        try {
            $this->service->pay($order->id, $this->method);

            return 'OK' . $nInvId;
        } catch (\DomainException $err) {
            return $err->getMessage();
        }
    }

    public function successCallback($merchant, $nInvId, $nOutSum, $shp)
    {
        return $this->goBack();
    }

    public function failCallback($merchant, $nInvId, $nOutSum, $shp)
    {
        /** @var Order */
        $order = $this->findOrder($nInvId);

        try {
            $this->service->fail($order->id, $this->method);

            return 'OK';
        } catch (\DomainException $err) {
            return $err->getMessage();
        }
    }

    private function findOrder($orderId): Order
    {
        if (!$order = $this->orders->findOneOrder(
            $this->yiiApp->user->id,
            $orderId
        )) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $order;
    }

    private function getMerchant(): Merchant
    {
        return $this->yiiApp->get('robokassa');
    }
}
