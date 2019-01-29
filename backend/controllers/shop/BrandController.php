<?php

namespace backend\controllers\shop;

use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use shop\services\manage\Shop\BrandManageService;
use shop\forms\manage\Shop\BrandForm;
use shop\entities\Shop\Brand;
use backend\forms\Shop\BrandSearch;
use shop\repositories\NotFoundException;

class BrandController extends Controller
{
     /* @property BrandManageService $service */
    private $service;
    private $yiiApp;

    public function __construct(
        $id,
        $module,
        BrandManageService $service,
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
        $searchModel = $this->getBrandSearch();
        $dataProvider = $searchModel->search($this->yiiApp->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'brand' => $this->getModel($id),
        ]);
    }

    /**
     * @return mixed
     */
    public function actionCreate()
    {
        $form = $this->getBrandForm();

        if ($form->load($this->yiiApp->request->post()) && $form->validate()) {
            try {
                $brand = $this->service->create($form);

                return $this->redirect(['view', 'id' => $brand->id]);
            } catch (\DomainException $err) {
                $this->setLogErrorFlash($err);
            }
        }

        return $this->render('create', [
            'model' => $form,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $brand = $this->getModel($id);
        $form = $this->getBrandForm($brand);

        if ($form->load($this->yiiApp->request->post()) && $form->validate()) {
            try {
                $this->service->edit($brand->id, $form);

                return $this->redirect(['view', 'id' => $brand->id]);
            } catch (\DomainException $err) {
                $this->setLogErrorFlash($err);
            }
        }

        return $this->render('update', [
            'model' => $form,
            'brand' => $brand,
        ]);
    }

    /**
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

    /**
     * Get Brand class object by $id
     *
     * @param integer $id
     * @return Brand the loaded model via BrandManageService
     * @throws NotFoundHttpException if the model cannot be found
     */
    private function getModel($id): Brand
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

    private function getBrandForm($brand = null): BrandForm
    {
        return new BrandForm($brand);
    }

    private function getBrandSearch(): BrandSearch
    {
        return new BrandSearch();
    }
}
