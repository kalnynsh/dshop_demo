<?php

namespace backend\controllers\shop;

use yii\web\Controller;
use yii\filters\VerbFilter;
use shop\services\manage\Shop\CharacteristicManageService;
use shop\forms\manage\Shop\CharacteristicForm;
use shop\entities\Shop\Characteristic;
use backend\forms\Shop\CharacteristicSearch;
use shop\repositories\NotFoundException;

class CharacteristicController extends Controller
{
    /* @property CharacteristicManageService $service */
    private $service;
    private $yiiApp;

    public function __construct(
        $id,
        $module,
        CharacteristicManageService $service,
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
        $searchModel = $this->getCharacteristicSearch();
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
            'characteristic' => $this->getModel($id),
        ]);
    }

    /**
     * @return mixed
     */
    public function actionCreate()
    {
        $form = $this->getCharacteristicForm();

        if ($form->load($this->yiiApp->request->post()) && $form->validate()) {
            try {
                $characteristic = $this->service->create($form);

                return $this->redirect(['view', 'id' => $characteristic->id]);
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
        $characteristic = $this->getModel($id);
        $form = $this->getCharacteristicForm($characteristic);

        if ($form->load($this->yiiApp->request->post()) && $form->validate()) {
            try {
                $this->service->edit($characteristic->id, $form);

                return $this->redirect(['view', 'id' => $characteristic->id]);
            } catch (\DomainException $err) {
                $this->setLogErrorFlash($err);
            }
        }

        return $this->render('update', [
            'model' => $form,
            'characteristic' => $characteristic,
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
     * Get Characteristic class object by $charId
     *
     * @param integer $charId
     * @return Characteristic the loaded model via CharacteristicManageService
     * @throws NotFoundException if the model cannot be found
     */
    protected function getModel($charId): Characteristic
    {
        try {
            return $this->service->get($charId);
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

    private function getCharacteristicForm($characteristic = null): CharacteristicForm
    {
        return new CharacteristicForm($characteristic);
    }

    private function getCharacteristicSearch()
    {
        return new CharacteristicSearch();
    }
}
