<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'u9af4J8jPL-zmgDQS5hQdKq6zHxjX5oe',
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
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
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
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'user', 'pluralize' => true,
                    'tokens' => ['{id}' => '<id:\\w+>'],
                    'extraPatterns' => [
                        'GET {id}' => 'view',
                        'GET {id}/visits' => 'visits',
                        'POST new' => 'create',
                        'POST {id}' => 'update',
//                        'GET {id}/age' => 'age',
//                        'GET upd' => 'upd',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'location',
                    'pluralize' => true,
                    'tokens' => ['{id}' => '<id:\\w+>'],
                    'extraPatterns' => [
                        'GET {id}' => 'view',
                        'GET {id}/avg' => 'avg',
                        'POST new' => 'create',
                        'POST {id}' => 'update',
//                        'GET {id}/age' => 'age',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'visit',
                    'pluralize' => true,
                    'tokens' => ['{id}' => '<id:\\w+>'],
                    'extraPatterns' => [
                        'GET {id}' => 'view',
                        'POST new' => 'create',
                        'POST {id}' => 'update',
                    ],
                ],
            ],
        ],
    ],
    'params' => $params,
];

define('YII_ENV_DEV', true);

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
