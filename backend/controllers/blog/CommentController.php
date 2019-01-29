<?php

namespace backend\controllers\blog;

use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use shop\services\manage\Blog\CommentManageService;
use shop\forms\manage\Blog\Post\CommentEditForm;
use shop\entities\Blog\Post\Post;
use shop\entities\Blog\Post\Comment;
use backend\forms\Blog\CommentSearch;

class CommentController extends Controller
{
    /* @property CommentManageService $service */
    private $service;
    private $yiiApp;

    public function __construct(
        $id,
        $module,
        CommentManageService $service,
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
        $searchModel = $this->getCommentSearch();
        $dataProvider = $searchModel->search($this->yiiApp->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param integer $post_id
     * @param integer $id
     * @return mixed
     */
    public function actionView($post_id, $id)
    {
        $post = $this->findModel($post_id);
        $comment = $post->getComment($id);

        return $this->render('view', [
            'post' => $post,
            'comment' => $comment
        ]);
    }

    /**
     * @param integer $post_id
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($post_id, $id)
    {
        $post = $this->findModel($post_id);
        $comment = $post->getComment($id);
        $form = $this->getCommentEditForm($comment);

        if ($form->load($this->yiiApp->request->post()) && $form->validate()) {
            try {
                $this->service->edit($post->id, $comment->id, $form);

                return $this->redirect([
                    'view',
                    'post_id' => $post->id,
                    'id' => $comment->id,
                ]);
            } catch (\DomainException $err) {
                $this->setLogErrorFlash($err);
            }
        }

        return $this->render('update', [
            'post' => $post,
            'comment' => $comment,
            'model' => $form,
        ]);
    }

    /**
     * @param integer $post_id
     * @param integer $id
     * @return mixed
     */
    public function actionActivate($post_id, $id)
    {
        $post = $this->findModel($post_id);
        $comment = $post->getComment($id);

        try {
            $this->service->activate($post->id, $comment->id);
        } catch (\DomainException $err) {
            $this->setLogErrorFlash($err);
        }

        return $this->redirect('view', [
            'post_id' => $post->id,
            'id' => $comment->id,
        ]);
    }

    /**
     * @param integer $post_id
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($post_id, $id)
    {
        $post = $this->findModel($post_id);
        $comment = $post->getComment($id);

        try {
            $this->service->remove($post->id, $comment->id);
        } catch (\DomainException $err) {
            $this->setLogErrorFlash($err);
        }

        return $this->redirect(['index']);
    }

    /**
     * Find Post object by $id
     *
     * @param integer $id
     * @return Post the loaded model via CommentManageService
     * @throws NotFoundHttpException if the model cannot be found
     */
    private function findModel($id): Post
    {
        if ($post = $this->service->findPost($id)) {
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

    private function getCommentEditForm(Comment $comment): CommentEditForm
    {
        return new CommentEditForm($comment);
    }

    private function getCommentSearch(): CommentSearch
    {
        return new CommentSearch($this->service);
    }
}
