<?php

namespace backend\controllers\shop;

use yii\web\Controller;
use yii\filters\VerbFilter;
use shop\services\manage\Shop\ModificationManageService;
use shop\forms\manage\Shop\Product\ModificationForm;
use shop\entities\Shop\Product\Product;
use shop\repositories\NotFoundException;

class ModificationController extends Controller
{
    /** @property ModificationManageService $service */
    private $service;
    private $yiiApp;

    public function __construct(
        $id,
        $module,
        ModificationManageService $service,
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
        return $this->redirect('shop/product');
    }

    /**
     * @param $product_id
     * @return mixed
     */
    public function actionCreate($product_id)
    {
        $product = $this->getModel($product_id);
        $form = $this->getModificationForm();

        if ($form->load($this->yiiApp->request->post()) && $form->validate()) {
            try {
                $this->service->addModification($product->id, $form);

                return $this->redirect([
                    'shop/product/view',
                    'id' => $product->id,
                    '#' => 'modifications'
                ]);
            } catch (\DomainException $err) {
                $this->setLogErrorFlash($err);
            }
        }

        return $this->render('create', [
            'product' => $product,
            'model' => $form,
        ]);
    }

    /**
     * @param integer $product_id
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($product_id, $id)
    {
        $product = $this->getModel($product_id);
        $modification = $product->getModification($id);
        $form = getModificationForm($modification);

        if ($form->load($this->yiiApp->request->post()) && $form->validate()) {
            try {
                $this->service->editModification($product->id, $modification->id, $form);

                return $this->redirect([
                    'shop/product/view',
                    'id' => $product->id,
                    '#' => 'modifications'
                ]);
            } catch (\DomainException $err) {
                $this->setLogErrorFlash($err);
            }
        }

        return $this->render('update', [
            'product' => $product,
            'model' => $form,
            'modification' => $modification,
        ]);
    }

    /**
     * @param $product_id
     * @param integer $id
     * @return mixed
     * @throws \DomainException
     */
    public function actionDelete($product_id, $id)
    {
        $product = $this->getModel($product_id);

        try {
            $this->service->removeModification($product->id, $id);
        } catch (\DomainException $e) {
            $this->yiiApp->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect([
            'shop/product/view',
            'id' => $product->id,
            '#' => 'modifications'
        ]);
    }

    /**
     * Get Product class object by $productId
     *
     * @param integer $productId
     * @return Product the loaded model
     * @throws NotFoundException if the model cannot be found
     */
    private function getModel($productId): Product
    {
        try {
            return $this->service->getProduct($productId);
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

    private function getModificationForm($modification = null): ModificationForm
    {
        return new ModificationForm($modification);
    }
}
