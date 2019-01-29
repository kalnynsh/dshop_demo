<?php

namespace backend\controllers;

use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use shop\services\manage\Content\PageManageService;
use shop\forms\manage\Content\PageForm;
use shop\entities\Content\Page;
use backend\forms\Content\PageSearch;

class PageController extends Controller
{
    /* @property PageManageService $service */
    private $service;
    private $yiiApp;

    public function __construct(
        $id,
        $module,
        PageManageService $service,
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
        $searchModel = $this->getPageSearch();
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
            'page' => $this->findModel($id),
        ]);
    }

    /**
     * @return mixed
     */
    public function actionCreate()
    {
        $form = $this->getPageForm();

        if ($form->load($this->yiiApp->request->post()) && $form->validate()) {
            try {
                $page = $this->service->create($form);

                return $this->redirect(['view', 'id' => $page->id]);
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
        $page = $this->findModel($id);
        $form = $this->getPageForm($page);

        if ($form->load($this->yiiApp->request->post()) && $form->validate()) {
            try {
                $this->service->edit($page->id, $form);

                return $this->redirect(['view', 'id' => $page->id]);
            } catch (\DomainException $err) {
                $this->setLogErrorFlash($err);
            }
        }

        return $this->render('update', [
            'model' => $form,
            'page' => $page,
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
     * Find Page object by $id
     *
     * @param integer $id
     * @return Page the loaded model via PageManageService
     * @throws NotFoundHttpException if the model cannot be found
     */
    private function findModel($id): Page
    {
        if ($page = $this->service->find($id)) {
            return $page;
        }

        throw new NotFoundHttpException('The requested page doesn`t exist.');
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

    private function getPageForm(Page $page = null): PageForm
    {
        return new PageForm($page);
    }

    private function getPageSearch(): PageSearch
    {
        return new PageSearch();
    }
}
