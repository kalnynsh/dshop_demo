<?php

namespace frontend\controllers\cabinet;

use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\authclient\ClientInterface;
use yii\authclient\AuthAction;

class NetworkController extends Controller
{
    public $layout = 'cabinet';

    private $service;
    private $yiiApp;

    public function __construct(
        $id,
        $module,
        Networkservice $service,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->yiiApp = \Yii::$app;
    }

    public function actions(): array
    {
        return [
            'attach' => [
                'class' => AuthAction::class,
                'successCallback' => [$this, 'onAuthSuccess'],
                'successUrl' => Url::to(['cabinet/default/index']),
            ]
        ];
    }

    public function onAuthSuccess(ClientInterface $client): void
    {
        $network = $client->getId();
        $attributes = $client->getUserAttributes();
        $identity = ArrayHelper::getValue($attributes, 'id');

        try {
            $this->service->attach($this->yiiApp->user->id, $network, $identity);
        } catch (\DomainException $err) {
            $this->yiiApp->errorHandler->logException($err);
            $this->yiiApp->session->setFlash('error', $err->getMessage());
        }
    }
}
