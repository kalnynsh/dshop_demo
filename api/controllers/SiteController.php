<?php

namespace api\controllers;

use yii\rest\Controller;

/**
 *
 * @OA\Info(title="Shop API", version="1.0.0")
 *
 * @OA\Server(url=API_HOST)
 *
 * @OA\Get(
 *     path="/api/",
 *     @OA\Response(
 *          response="200",
 *          description="HTTP JSON API"
 *      )
 * )
 */
class SiteController extends Controller
{
    public function actionIndex(): array
    {
        return [
            'version' => '1.0.0',
        ];
    }
}
