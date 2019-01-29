<?php

namespace backend\controllers\shop;

use yii\web\Controller;
use yii\filters\VerbFilter;
use shop\services\manage\Shop\DeliveryManageService;
use shop\repositories\NotFoundException;
use shop\forms\manage\Shop\DeliveryForm;
use shop\entities\Shop\Delivery;
use backend\forms\Shop\DeliverySearch;

class DeliveryController extends Controller
{
     /** @property DeliveryManageService $service */
    private $service;
    private $yiiApp;

    public function __construct(
        $id,
        $module,
        DeliveryManageService $service,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->yiiApp = \Yii::$app;
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = $this->getDeliverySearch();

        $dataProvider = $searchModel
            ->search($this->yiiApp->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * actionView function
     *
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'method' => $this->getModel($id),
        ]);
    }

    /**
    * @return mixed
    */
    public function actionCreate()
    {
        $form = $this->getDeliveryForm();

        if ($form->load($this->yiiApp->request->post())
            && $form->validate()
        ) {
            try {
                $method = $this->service->create($form);

                return $this->redirect([
                    'view',
                    'id' => $method->id,
                ]);
            } catch (\DomainException $err) {
                $this->setLogErrorFlash($err);
            }
        }

        return $this->render('create', [
            'model' => $form,
        ]);
    }

    /**
     * actionUpdate function
     *
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $method = $this->getModel($id);
        $form = $this->getDeliveryForm($method);

        if ($form->load($this->yiiApp->request->post())
            && $form->validate()
        ) {
            try {
                $this->service->edit($method->id, $form);

                return $this->redirect([
                    'view',
                    'id' => $method->id,
                ]);
            } catch (\DomainException $err) {
                $this->setLogErrorFlash($err);
            }
        }

        return $this->render('update', [
            'model' => $form,
            'method' => $method,
        ]);
    }

    /**
     * actionDelete function
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $this->service->remove($id);
        } catch (\DomainException $err) {
            $this->setLogErrorFlash($err);
        }

        return $this->redirect(['index']);
    }

    private function getDeliverySearch(): DeliverySearch
    {
        return new DeliverySearch();
    }

    private function getDeliveryForm(Delivery $method = null): DeliveryForm
    {
        return new DeliveryForm($method);
    }

    /**
     * Get Delivery class object by $id
     *
     * @param int $id
     * @return Delivery
     * @throws NotFoundException
     */
    private function getModel($id): Delivery
    {
        try {
            return $this->service->get($id);
        } catch (NotFoundException $err) {
            $this->setLogErrorFlash($err);
        }
    }

    private function setFlash($type, $message)
    {
        $this->yiiApp->session->setFlash($type, $message);
    }

    private function setLogErrorFlash($error)
    {
        $this->yiiApp->errorHandler->logException($error);
        $this->setFlash('error', $error->getMessage());
    }
}
