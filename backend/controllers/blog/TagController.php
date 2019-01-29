<?php

namespace backend\controllers\blog;

use yii\web\Controller;
use yii\filters\VerbFilter;
use shop\services\manage\Blog\TagManageService;
use shop\forms\manage\Blog\TagForm;
use shop\entities\Blog\Tag;
use backend\forms\Blog\TagSearch;

class TagController extends Controller
{
    /** @property TagManageService $service */
    private $service;
    private $yiiApp;

    public function __construct(
        $id,
        $module,
        TagManageService $service,
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
        $searchModel = $this->getTagSearch();
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
            'tag' => $this->findModel($id),
        ]);
    }

    /**
     * @return mixed
     */
    public function actionCreate()
    {
        $form = $this->getTagForm();

        if ($form->load($this->yiiApp->request->post()) && $form->validate()) {
            try {
                $tag = $this->service->create($form);

                return $this->redirect(['view', 'id' => $tag->id]);
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
        $tag = $this->findModel($id);
        $form = $this->getTagForm($tag);

        if ($form->load($this->yiiApp->request->post()) && $form->validate()) {
            try {
                $this->service->edit($tag->id, $form);

                return $this->redirect(['view', 'id' => $tag->id]);
            } catch (\DomainException $err) {
                $this->setLogErrorFlash($err);
            }
        }

        return $this->render('update', [
            'model' => $form,
            'tag' => $tag,
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
     * Get Tag class object by $id
     *
     * @param integer $id
     * @return Tag the found model`s entity
     * @throws NotFoundHttpException if the entity cannot be found
     */
    private function findModel($id): Tag
    {
        if ($tag = $this->service->find($id)) {
            return $tag;
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

    private function getTagSearch(): TagSearch
    {
        return new TagSearch();
    }

    private function getTagForm(Tag $tag = null): TagForm
    {
        return new TagForm($tag);
    }
}
