<?php

namespace backend\controllers\shop;

use yii\web\Controller;
use yii\filters\VerbFilter;
use shop\services\manage\Shop\CategoryManageService;
use shop\repositories\NotFoundException;
use shop\forms\manage\Shop\CategoryForm;
use shop\entities\Shop\Category;
use backend\forms\Shop\CategorySearch;

class CategoryController extends Controller
{
    /* @property CategoryManageService $service */
    private $service;
    private $yiiApp;

    public function __construct(
        $id,
        $module,
        CategoryManageService $service,
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
        $searchModel = $this->getCategorySearch();
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
            'category' => $this->getModel($id),
        ]);
    }

    /**
     * @return mixed
     */
    public function actionCreate()
    {
        $form = $this->getCategoryForm();

        if ($form->load($this->yiiApp->request->post()) && $form->validate()) {
            try {
                $category = $this->service->create($form);

                return $this->redirect(['view', 'id' => $category->id]);
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
        $category = $this->getModel($id);
        $form = $this->getCategoryForm($category);

        if ($form->load($this->yiiApp->request->post()) && $form->validate()) {
            try {
                $this->service->edit($category->id, $form);

                return $this->redirect(['view', 'id' => $category->id]);
            } catch (\DomainException $err) {
                $this->setLogErrorFlash($err);
            }
        }

        return $this->render('update', [
            'model' => $form,
            'category' => $category,
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
     * @param integer $id
     * @return mixed
     */
    public function actionMoveUp($id)
    {
        $this->service->moveUp($id);

        return $this->redirect(['index']);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionMoveDown($id)
    {
        $this->service->moveDown($id);

        return $this->redirect(['index']);
    }

    /**
     * Get Category class object by $id
     *
     * @param integer $id
     * @return Category the loaded model via CategoryManageService
     * @throws NotFoundException if the model cannot be found
     */
    private function getModel($id): Category
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

    private function getCategoryForm(Category $category = null): CategoryForm
    {
        return new CategoryForm($category);
    }

    private function getCategorySearch(): CategorySearch
    {
        return new CategorySearch();
    }
}
