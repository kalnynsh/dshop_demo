<?php

namespace frontend\controllers\cabinet;

use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use shop\services\cabinet\WishlistService;
use shop\readModels\Shop\ProductReadRepository;
use Yii;

class WishlistController extends Controller
{
    public $layout = 'cabinet';

    private $service;
    private $products;
    private $yiiApp;
    private $currentUserId = null;

    public function __construct(
        $id,
        $module,
        WishlistService $service,
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
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'add' => ['POST'],
                    'delete' => ['POST'],
                ]
            ]
        ];
    }

    /**
     * actionIndex function
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = $this->products->getWishlist($this->getCurrentUserId());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * actionAdd function
     *
     * @param string $id
     * @return mixed
     */
    public function actionAdd($id)
    {
        try {
            $this->service->add($this->getCurrentUserId(), $id);

            $this->setFlash(
                'success',
                'You are successfuly added this product to wish list!'
            );
        } catch (\DomainException $err) {
            $this->logAndSetErrorFlash($err);
        }

        return $this->redirect($this->yiiApp->request->referrer ?: ['index']);
    }

    /**
     * actionDelete function
     *
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $this->service->remove($this->getCurrentUserId(), $id);
        } catch (\DomainException $e) {
            $this->logAndSetErrorFlash($e);
        }

        return $this->redirect(['index']);
    }

    private function getCurrentUserId(): int
    {
        if (!$this->currentUserId) {
            $this->currentUserId = (int)$this->yiiApp->user->id;
        }

        return $this->currentUserId;
    }

    private function setFlash($type, $message): void
    {
        $this->yiiApp->session->setFlash($type, $message);
    }

    private function logAndSetErrorFlash(\DomainException $error): void
    {
        $this->yiiApp->errorHandler->logException($error);
        $this->setFlash('error', $error->getMessage());
    }
}
