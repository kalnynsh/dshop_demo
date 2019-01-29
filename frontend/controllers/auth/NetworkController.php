<?php

namespace frontend\controllers\auth;

use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\authclient\ClientInterface;
use yii\authclient\AuthAction;
use shop\services\auth\NetworkService;
use Yii;

class NetworkController extends Controller
{
    private $service;
    private $yiiApp;

    public function __construct(
        $id,
        $module,
        NetworkService $service,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->yiiApp = \Yii::$app;
    }

    public function actions()
    {
        return [
            'auth' => [
                'class' => AuthAction::class,
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function onAuthSuccess(ClientInterface $client): void
    {
        $network = $client->getId();
        $attributes = $client->getUserAttributes();
        $identity = ArrayHelper::getValue($attributes, 'id');

        try {
            $user = $this->service->auth($network, $identity);
            $this->yiiApp
                ->user
                ->login($user, $this->yiiApp->params['user.rememberMeDuration']);
        } catch (\DomainException $e) {
            $this->yiiApp->errorHandler->logException($e);
            $this->yiiApp->session->setFlash('error', $e->getMessage());
        }
    }
}
