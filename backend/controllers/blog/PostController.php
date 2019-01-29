<?php

namespace backend\controllers\blog;

use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use shop\services\manage\Blog\PostManageService;
use shop\forms\manage\Blog\Post\PostForm;
use backend\forms\Blog\PostSearch;
use shop\entities\Blog\Post\Post;

class PostController extends Controller
{
    /* @property PostManageService $service */
    private $service;
    private $yiiApp;

    public function __construct(
        $id,
        $module,
        PostManageService $service,
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
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = $this->getPostSearch();
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
            'post' => $this->findModel($id),
        ]);
    }

    /**
     * @return mixed
     */
    public function actionCreate()
    {
        $form = $this->getPostForm();

        if ($form->load($this->yiiApp->request->post()) && $form->validate()) {
            try {
                $post = $this->service->create($form);

                return $this->redirect(['view', 'id' => $post->id]);
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
        $post = $this->findModel($id);
        $form = $this->getPostForm($post);

        if ($form->load($this->yiiApp->request->post()) && $form->validate()) {
            try {
                $this->service->edit($post->id, $form);

                return $this->redirect(['view', 'id' => $post->id]);
            } catch (\DomainException $err) {
                $this->setLogErrorFlash($err);
            }
        }

        return $this->render('update', [
            'model' => $form,
            'post' => $post,
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
     * Activate Post object by $id
     *
     * @param integer $id
     * @return mixed
     * @throws \DomainException
     */
    public function actionActivate($id)
    {
        try {
            $this->service->activate($id);
        } catch (\DomainException $err) {
            $this->setLogErrorFlash($err);
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Set to Draft Post object by $id
     *
     * @param integer $id
     * @return mixed
     * @throws \DomainException
     */
    public function actionDraft($id)
    {
        try {
            $this->service->draft($id);
        } catch (\DomainException $err) {
            $this->setLogErrorFlash($err);
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Find Post class object by $id
     *
     * @param integer $id
     * @return Post the loaded model via PostManageService
     * @throws NotFoundHttpException if the model cannot be found
     */
    private function findModel($id): Post
    {
        if ($post = $this->service->find($id)) {
            return $post;
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

    private function getPostForm(Post $post = null): PostForm
    {
        return new PostForm($post);
    }

    private function getPostSearch(): PostSearch
    {
        return new PostSearch();
    }
}
