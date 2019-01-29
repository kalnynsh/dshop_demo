<?php

namespace backend\controllers\blog;

use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use shop\services\manage\Blog\CategoryManageService;
use shop\forms\manage\Blog\CategoryForm;
use shop\entities\Blog\Category;
use backend\forms\Blog\CategorySearch;

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
            'category' => $this->findModel($id),
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
        $category = $this->findModel($id);
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
     * Find Category class object by $id
     *
     * @param integer $id
     * @return Category the loaded model via CategoryManageService
     * @throws NotFoundHttpException if the model cannot be found
     */
    private function findModel($id): Category
    {
        if ($category = $this->service->find($id)) {
            return $category;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
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
