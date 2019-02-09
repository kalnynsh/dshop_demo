<?php

$db_config = \parse_ini_file(__DIR__ . '/db_config.ini');

return [
    'controllerMap' => [
        'heroku' => [
            'class' => 'purrweb\heroku\HerokuGeneratorController',
        ],
    ],
    'components' => [
        'db' => [
            'class' => $db_config['class'],
            'dsn' => $db_config['dsn'],
            'username' => $db_config['username'],
            'password' => $db_config['password'],
            'charset' => $db_config['charset'],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
            'messageConfig' => [
                'from' => ['support@example.com' => 'Shop']
            ],
        ],
        'robokassa' => [
            'class' => '\robokassa\Merchant',
            'baseUrl' => 'https://auth.robokassa.ru/Merchant/Index.aspx',
            'sMerchantLogin' => 'Test1999',
            'sMerchantPass1' => 'password_1',
            'sMerchantPass2' => '',
            'isTest' => 1,
            // 'isTest' => !YII_ENV_PROD,
        ],
    ],
];
