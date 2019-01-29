<?php

namespace frontend\controllers\auth;

use yii\web\Controller;
use yii\web\BadRequestHttpException;
use shop\services\auth\PasswordResetService;
use shop\forms\auth\ResetPasswordForm;
use shop\forms\auth\PasswordResetRequestForm;

class ResetController extends Controller
{
    public $layout = 'cabinet';
    private $service;
    private $yiiApp;

    public function __construct(
        $id,
        $module,
        PasswordResetService $service,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->yiiApp = \Yii::$app;
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequest()
    {
        $form = new PasswordResetRequestForm();

        if ($form->load($this->yiiApp->request->post()) && $form->validate()) {
            try {
                $this->service->request($form);

                $this->yiiApp->session->setFlash(
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
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws \DomainException
     */
    public function actionConfirm(string $token)
    {
        try {
            $this->service->validateToken($token);
        } catch (\DomainException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        $form = new ResetPasswordForm();

        if ($form->load($this->yiiApp->request->post())
            && $form->validate()
        ) {
            try {
                $this->service->reset($token, $form);

                $this->yiiApp->session->setFlash(
                    'success',
                    'New password saved.'
                );

                return $this->goHome();
            } catch (\DomainException $err) {
                $this->setLogErrorFlash($err);
            }
        }

        return $this->render('confirm', [
            'model' => $form,
        ]);
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
