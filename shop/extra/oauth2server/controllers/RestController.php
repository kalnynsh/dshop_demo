<?php

namespace shop\extra\oauth2server\controllers;

use yii\helpers\ArrayHelper;
use shop\extra\oauth2server\filters\ErrorToExceptionFilter;

class RestController extends \yii\rest\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'exceptionFilter' => [
                'class' => ErrorToExceptionFilter::className()
            ],
        ]);
    }

    public function actionToken()
    {
        $response = $this->module->getServer()->handleTokenRequest();

        return $response->getParameters();
    }
}
