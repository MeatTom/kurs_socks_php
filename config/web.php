<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name'=>'socks_shop',
    'language'=>'ru-RU',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'Leonid',
            'parsers' => [
       'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
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
        'db' => $db,
         'urlManager' => [
'enablePrettyUrl' => true,
'enableStrictParsing' => true,
'showScriptName' => false,
'rules' => [
    'POST register' => 'users/create',
    'POST auth' => 'users/login',
    'GET user' => 'users/user',
    'PATCH email'=> 'users/email',
    'POST tovars'=> 'tovar/create',
    'DELETE tovars/<id:\d+>'=> 'tovar/delete',
    'PATCH tovars/<id:\d+>'=> 'tovar/update',
    'GET tovars/<id:\d+>'=> 'tovar/view',
    'GET tovars'=> 'tovar/view_all',
    'POST add_cart' => 'cart/add',
    'GET show_cart' => 'cart/view',
    'DELETE del_cart/<id:\d+>' => 'cart/delete',
    'PATCH amount_cart/<id:\d+>' => 'cart/amount',
    'POST order' => 'orders/order',
    'GET orders' => 'orders/view_all',
    'GET order/<id:\d+>' => 'orders/view',
],
]

    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '*'],
    ];
}

return $config;
