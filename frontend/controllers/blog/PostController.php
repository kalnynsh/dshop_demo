<?php

namespace frontend\controllers\blog;

use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\filters\AccessControl;
use shop\services\Blog\CommentService;
use shop\readModels\Blog\TagReadRepository;
use shop\readModels\Blog\PostReadRepository;
use shop\readModels\Blog\CategoryReadRepository;
use shop\forms\Blog\CommentForm;

/**
 * PostController class
 *
 * @property string                 $layout
 * @property CommentService         $service
 * @property PostReadRepository     $posts
 * @property CategoryReadRepository $categories
 * @property TagReadRepository      $tags
 * @property                        $yiiApp
 */
class PostController extends Controller
{
    public $layout = 'blog';

    private $service;
    private $posts;
    private $categories;
    private $tags;
    private $yiiApp;

    public function __construct(
        $id,
        $module,
        CommentService $service,
        PostReadRepository $posts,
        CategoryReadRepository $categories,
        TagReadRepository $tags,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->posts = $posts;
        $this->categories = $categories;
        $this->tags = $tags;
        $this->yiiApp = \Yii::$app;
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['comment'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = $this->posts->getAll();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * actionCategory function
     *
     * @param string $slug
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionCategory($slug)
    {
        if (!$category = $this->categories->findBySlug($slug)) {
            throw new NotFoundHttpException('The requested page does not exists.');
        }

        $dataProvider = $this->posts->getAllByCategory($category);

        return $this->render('category', [
            'category' => $category,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * actionTag function
     *
     * @param string $slug
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionTag($slug)
    {
        if (!$tag = $this->tags->findBySlug($slug)) {
            throw new NotFoundHttpException('The requested page does not exists.');
        }

        $dataProvider = $this->posts->getAllByTag($tag);

        return $this->render('tag', [
            'tag' => $tag,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * actionPost function - get Post by ID
     *
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionPost($id)
    {
        if (!$post = $this->posts->find($id)) {
            throw new NotFoundHttpException('The requested page does not exists.');
        }

        return $this->render('post', [
            'post' => $post,
        ]);
    }

    /**
     * actionComment function - add comment to post by ID
     *
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionComment($id)
    {
        if (!$post = $this->posts->find($id)) {
            throw new NotFoundHttpException('The requested page does not exists.');
        }

        $form = $this->getCommentForm();

        if ($form->load($this->yiiApp->request->post())
            && $form->validate()
        ) {
            try {
                $comment = $this->service->create(
                    $post->id,
                    $this->yiiApp->user->id,
                    $form
                );

                return $this->redirect([
                    'post',
                    'id' => $post->id,
                    '#' => 'comment_' . $comment->id,
                ]);
            } catch (\DomainException $err) {
                $this->yiiApp->errorHandler->logException($err);
                $this->yiiApp->session->setFlash('error', $err->getMessage());
            }
        }

        return $this->render('comment', [
            'post' => $post,
            'model' => $form,
        ]);
    }

    private function getCommentForm(): CommentForm
    {
        return new CommentForm();
    }
}
