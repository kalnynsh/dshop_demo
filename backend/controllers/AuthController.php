<?php

namespace backend\controllers;

use yii\web\Controller;
use yii\filters\VerbFilter;
use shop\services\auth\AuthService;
use shop\forms\auth\LoginForm;

class AuthController extends Controller
{
    private $authService;
    private $yiiApp;

    public function __construct($id, $module, AuthService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->authService = $service;
        $this->yiiApp = \Yii::$app;
    }

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionLogin()
    {
        if (!$this->yiiApp->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'main-login';
        $form = $this->getLoginForm();

        if ($form->load($this->yiiApp->request->post()) && $form->validate()) {
            try {
                $user = $this->authService->auth($form);
                $this->yiiApp->user->login($user, $form->rememberMe ? 3600 * 24 * 30 : 0);

                return $this->goBack();
            } catch (\DomainException $err) {
                $this->setLogErrorFlash($err);
            }
        }

        return $this->render('login', [
            'model' => $form,
        ]);
    }

    /**
     * @return mixed
     */
    public function actionLogout()
    {
        $this->yiiApp->user->logout();

        return $this->goHome();
    }

    private function getLoginForm(): LoginForm
    {
        return new LoginForm();
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
}
