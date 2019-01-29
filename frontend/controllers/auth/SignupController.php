<?php

namespace frontend\controllers\auth;

use yii\web\Controller;
use yii\filters\AccessControl;
use shop\services\auth\SignupService;
use shop\forms\auth\SignupForm;

class SignupController extends Controller
{
    public $layout = 'blank';

    private $service;
    private $yiiApp;

    public function __construct(
        $id,
        $module,
        SignupService $service,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->yiiApp = \Yii::$app;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['request'],
                'rules' => [
                    [
                        'actions' => ['request'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    public function actionRequest()
    {
        $form = $this->getSignupForm();

        if ($form->load($this->yiiApp->request->post()) && $form->validate()) {
            try {
                $this->service->signup($form);

                $this->setFlash(
                    'success',
                    'Check your email for further instructions.'
                );

                return $this->goHome();
            } catch (\DomainException $err) {
                $this->setLogErrorFlash($err);
            }
        }

        return $this->render('request', [
            'model' => $form,
        ]);
    }

    /**
     * Confirm user's email
     *
     * @param string $token
     * @return void
     */
    public function actionConfirm($token)
    {
        try {
            $this->service->confirm($token);
            $this->setFlash('success', 'Your email confirmed.');

            return $this->redirect(['auth/auth/login']);
        } catch (\DomainException $err) {
            $this->setLogErrorFlash($err);

            return $this->goHome();
        }
    }

    private function setLogErrorFlash($error)
    {
        $this->yiiApp->errorHandler->logException($error);
        $this->setFlash('error', $error->getMessage());
    }

    private function setFlash($type, $message)
    {
        $this->yiiApp->session->setFlash($type, $message);
    }

    private function getSignupForm(): SignupForm
    {
        return new SignupForm();
    }
}
