<?php

namespace frontend\controllers\cabinet;

use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\filters\AccessControl;
use shop\readModels\Shop\OrderReadRepository;

class OrderController extends Controller
{
    public $layout = 'cabinet';

    private $orders;
    private $yiiApp;

    public function __construct(
        $id,
        $module,
        OrderReadRepository $orders,
        $config = []
    ) {
        parent::__construct($id, $module, $config);

        $this->orders = $orders;
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
                ]
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $userId = $this->getUserId();

        $dataProvider = $this
            ->orders
            ->getDataProvider($userId);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param int $id
     *
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $userId = $this->getUserId();

        if (!$order = $this->orders->findOneOrder($userId, $id)) {
            return new NotFoundHttpException('The requested page not exists.');
        }

        return $this->render('view', [
            'order' => $order,
        ]);
    }

    private function getUserId(): int
    {
        return $this->yiiApp->user->id;
    }
}
