<?php

namespace frontend\controllers\auth;

use yii\web\Controller;
use shop\services\auth\AuthService;
use shop\forms\auth\LoginForm;
use shop\entities\User\User;
use common\auth\Identity;

class AuthController extends Controller
{
    private $service;
    private $yiiApp;

    public function __construct(
        $id,
        $module,
        AuthService $service,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->yiiApp = \Yii::$app;
    }

    /**
     * Log in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!$this->yiiApp->user->isGuest) {
            return $this->goHome();
        }

        $form = $this->getLoginForm();

        if ($form->load($this->yiiApp->request->post()) && $form->validate()) {
            try {
                $user = $this->service->auth($form);
                $identity = $this->getIdentity($user);

                $duration = $form->rememberMe
                    ? $this->yiiApp->params['user.rememberMeDuration'] : 0;

                $this->yiiApp->user->login(
                    $identity,
                    $duration
                );

                return $this->goBack();
            } catch (\DomainException $e) {
                $this->yiiApp->errorHandler->logException($e);
                $this->yiiApp->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render(
            'login',
            ['model' => $form]
        );
    }

    /**
     * Logs out the current user.
     *
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

    private function getIdentity(User $user): Identity
    {
        return new Identity($user);
    }
}
