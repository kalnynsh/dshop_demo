<?php

namespace frontend\controllers\cabinet;

use yii\web\Controller;
use yii\filters\AccessControl;
use shop\services\cabinet\ProfileService;
use shop\forms\manage\UserProfile\PrifileEditForm;
use shop\entities\User\User;
use yii\web\NotFoundHttpException;

class ProfileController extends Controller
{
    public $layout = 'cabinet';

    private $service;
    private $yiiApp;

    public function __construct(
        $id,
        $module,
        ProfileService $service,
        $config = []
    ) {
        parent::__construct($id, $module, $config);

        $this->service = $service;
        $this->yiiApp = \Yii::$app;
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ]
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionEdit()
    {
        $id = $this->yiiApp->user->id;
        $user = $this->findUser($id);
        $form = $this->getProfileEditForm($user);

        if (
            $form->load($this->yiiApp->request->post())
            && $form->validate()
        ) {
            try {
                $this->service->edit($user->id, $form);

                return $this->redirect(['/cabinet/default/index', 'id' => $user->id]);
            } catch (\DomainException $error) {
                $this->yiiApp->errorHandler->logException($error);
                $this->yiiApp->session->setFlash('error', $error->getMessage());
            }
        }

        return $this->render('edit', [
            'model' => $form,
            'user' => $user,
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    private function findUser($id): ?User
    {
        if ($user = $this->service->find($id)) {
            return $user;
        }

        throw new NotFoundHttpException('The requested page dose not exist.');
    }

    private function getProfileEditForm($user): PrifileEditForm
    {
        return new PrifileEditForm($user);
    }
}
