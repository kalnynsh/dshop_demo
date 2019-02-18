<?php

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'aliases' => [
        '@staticRoot' => $params['staticPath'],
        '@static' => $params['staticHostInfo'],
    ],
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => [
        'log',
        'common\bootstrap\SetUp',
        [
            'class' => 'yii\filters\ContentNegotiator',
            'formats' => [
                'application/json' => 'json',
                'application/xml' => 'xml',
            ],
        ],
        \shop\extra\oauth2server\Bootstrap::class,
    ],
    'modules' => [
        'oauth2' => [
            'class' => \shop\extra\oauth2server\Module::class,
            'tokenParamName' => 'access_token',
            'tokenAccessLifetime' => 3600 * 24,
            'storageMap' => [
                'user_credentials' => \common\auth\Identity::class,
            ],
            'grantTypes' => [
                'user_credentials' => [
                    'class' => \OAuth2\GrantType\UserCredentials::class,
                ],
                'refresh_token' => [
                    'class' => \OAuth2\GrantType\RefreshToken::class,
                    'always_issue_new_refresh_token' => true,
                ],
            ],
        ],
    ],
    'components' => [
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'response' => [
            'formatters' => [
                'json' => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG,
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'common\auth\Identity',
            'enableAutoLogin' => false,
            'enableSession' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                'profile' => 'user/profile/index',
                'POST oauth2/<action:\w+>' => 'oauth2/rest/<action>',

                'GET shop/products/<id:\d+>' => 'shop/product/view',
                'GET shop/products/category/<id:\d+>' => 'shop/product/category',
                'GET shop/products/brand/<id:\d+>' => 'shop/product/brand',
                'GET shop/products/tag/<id:\d+>' => 'shop/product/tag',
                'GET shop/products' => 'shop/product/index',
                'shop/products/<id:\d+>/wish' => 'shop/wishlist/add',

                'GET shop/wishlist' => 'shop/wishlist/index',
                'DELETE shop/wishlist/<id:\d+>' => 'shop/wishlist/delete',
            ],
        ],
    ],
    'as authenticator' => [
        'class' => shop\extra\oauth2server\filters\auth\CompositeAuth::class,
        'except' => ['site/index', 'oauth2/rest/token'],
        'authMethods' => [
            [
                'class' => yii\filters\auth\HttpBearerAuth::class
            ],
            [
                'class' => yii\filters\auth\QueryParamAuth::class,
                'tokenParam' => 'accessToken',
            ],
        ],
    ],
    'as access' => [
        'class' => 'yii\filters\AccessControl',
        'except' => [
            'site/index',
            'oauth2/rest/token',
        ],
        'rules' => [
            [
                'allow' => true,
                'roles' => ['@'],
            ],
        ],
    ],
    'as exceptionFilter' => [
        'class' => shop\extra\oauth2server\filters\ErrorToExceptionFilter::class,
    ],
    'params' => $params,
];
