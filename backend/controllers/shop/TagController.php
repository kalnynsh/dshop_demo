<?php

namespace backend\controllers\shop;

use yii\web\Controller;
use yii\filters\VerbFilter;
use shop\services\manage\Shop\TagManageService;
use shop\repositories\NotFoundException;
use shop\forms\manage\Shop\TagForm;
use shop\entities\Shop\Tag;
use backend\forms\Shop\TagSearch;

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
            'tag' => $this->getModel($id),
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
        $tag = $this->getModel($id);
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
     * @return Tag the loaded model`s entity
     * @throws NotFoundException if the entity cannot be found
     */
    private function getModel($id): Tag
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

    private function getTagSearch(): TagSearch
    {
        return new TagSearch();
    }

    private function getTagForm(Tag $tag = null): TagForm
    {
        return new TagForm($tag);
    }
}
