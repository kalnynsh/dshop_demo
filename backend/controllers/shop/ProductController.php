<?php

namespace backend\controllers\shop;

use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use shop\services\manage\Shop\ProductManageService;
use shop\forms\manage\Shop\Product\QuantityForm;
use shop\forms\manage\Shop\Product\ProductEditForm;
use shop\forms\manage\Shop\Product\ProductCreateForm;
use shop\forms\manage\Shop\Product\PriceForm;
use shop\forms\manage\Shop\Product\PhotosForm;
use shop\entities\Shop\Product\Product;
use shop\entities\Shop\Product\Modification;
use backend\forms\Shop\ProductSearch;
use shop\repositories\NotFoundException;

class ProductController extends Controller
{
    private $service;
    private $yiiApp;

    public function __construct(
        $id,
        $module,
        ProductManageService $service,
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
                    'activate' => ['POST'],
                    'draft' => ['POST'],
                    'delete-photo' => ['POST'],
                    'move-photo-up' => ['POST'],
                    'move-photo-down' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = $this->getProductSearch();
        $dataProvider = $searchModel->search($this->yiiApp->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     * @throws \DomainException
     */
    public function actionView($id)
    {
        $product = $this->getModel($id);

        $modificationsProvider = $this->getActiveDataProvider([
            'query' => $product->getModifications()->orderBy('name'),
            'key' => function (Modification $modification) use ($product) {
                return [
                    'product_id' => $product->id,
                    'id' => $modification->id,
                ];
            },
            'pagination' => false,
        ]);

        $photosForm = $this->getPhotosForm();

        if ($photosForm->load($this->yiiApp->request->post()) && $photosForm->validate()) {
            try {
                $this->service->addPhotos($product->id, $photosForm);

                return $this->redirect(['view', 'id' => $product->id]);
            } catch (\DomainException $err) {
                $this->setLogErrorFlash($err);
            }
        }
        return $this->render('view', [
            'product' => $product,
            'modificationsProvider' => $modificationsProvider,
            'photosForm' => $photosForm,
        ]);
    }

    /**
     * @return mixed
     */
    public function actionCreate()
    {
        $form = $this->getProductCreateForm();

        if ($form->load($this->yiiApp->request->post()) && $form->validate()) {
            try {
                $product = $this->service->create($form);

                return $this->redirect(['view', 'id' => $product->id]);
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
        $product = $this->getModel($id);
        $form = $this->getProductEditForm($product);

        if ($form->load($this->yiiApp->request->post()) && $form->validate()) {
            try {
                $this->service->edit($product->id, $form);

                return $this->redirect(['view', 'id' => $product->id]);
            } catch (\DomainException $e) {
                $this->setLogErrorFlash($e);
            }
        }
        return $this->render('update', [
            'model' => $form,
            'product' => $product,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionPrice($id)
    {
        $product = $this->getModel($id);
        $form = $this->getPriceForm($product);

        if ($form->load($this->yiiApp->request->post()) && $form->validate()) {
            try {
                $this->service->changePrice($product->id, $form);

                return $this->redirect(['view', 'id' => $product->id]);
            } catch (\DomainException $e) {
                $this->setLogErrorFlash($e);
            }
        }

        return $this->render('price', [
            'model' => $form,
            'product' => $product,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionQuantity($id)
    {
        $product = $this->getModel($id);
        $form = $this->getQuantityForm($product);

        if ($form->load($this->yiiApp->request->post()) && $form->validate()) {
            try {
                $this->service->changeQuantity($product->id, $form);

                return $this->redirect(['view', 'id' => $product->id]);
            } catch (\DomainException $e) {
                $this->setLogErrorFlash($e);
            }
        }

        return $this->render('quantity', [
            'model' => $form,
            'product' => $product,
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
        } catch (\DomainException $e) {
            $this->setLogErrorFlash($e);
        }
        return $this->redirect(['index']);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionActivate($id)
    {
        try {
            $this->service->activate($id);
        } catch (\DomainException $e) {
            $this->setLogErrorFlash($e);
        }
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionDraft($id)
    {
        try {
            $this->service->draft($id);
        } catch (\DomainException $e) {
            $this->setLogErrorFlash($e);
        }
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * @param integer $id
     * @param $photo_id
     * @return mixed
     */
    public function actionDeletePhoto($id, $photo_id)
    {
        try {
            $this->service->removePhoto($id, $photo_id);
        } catch (\DomainException $e) {
            $this->setLogErrorFlash($e);
        }
        return $this->redirect(['view', 'id' => $id, '#' => 'photos']);
    }

    /**
     * @param integer $id
     * @param $photo_id
     * @return mixed
     */
    public function actionMovePhotoUp($id, $photo_id)
    {
        $this->service->movePhotoUp($id, $photo_id);
        return $this->redirect(['view', 'id' => $id, '#' => 'photos']);
    }

    /**
     * @param integer $id
     * @param $photo_id
     * @return mixed
     */
    public function actionMovePhotoDown($id, $photo_id)
    {
        $this->service->movePhotoDown($id, $photo_id);
        return $this->redirect(['view', 'id' => $id, '#' => 'photos']);
    }

    /**
     * Get Product class object by $id
     *
     * @param integer $id
     * @return Product the loaded model via ProductManageService
     * @throws NotFoundException if the model cannot be found
     */
    private function getModel($id): Product
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

    private function getActiveDataProvider(array $arr): ActiveDataProvider
    {
        return new ActiveDataProvider($arr);
    }

    private function getPhotosForm(): PhotosForm
    {
        return new PhotosForm();
    }

    private function getProductCreateForm(): ProductCreateForm
    {
        return new ProductCreateForm();
    }

    private function getProductEditForm(Product $product): ProductEditForm
    {
        return new ProductEditForm($product);
    }

    private function getPriceForm(Product $product): PriceForm
    {
        return new PriceForm($product);
    }

    private function getQuantityForm(Product $product): QuantityForm
    {
        return new QuantityForm($product);
    }

    private function getProductSearch(): ProductSearch
    {
        return new ProductSearch();
    }
}
