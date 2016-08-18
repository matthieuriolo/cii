<?php

$config = [
    'id' => 'web-installation',
    'basePath' => dirname(__DIR__),
    'runtimePath' => __DIR__ . '/../../runtime',
    'vendorPath' => __DIR__ . '/../../modules/cii/vendor',
    'aliases' => [
        '@cii' => __DIR__ . '/../../modules/cii/vendor/cii',
        //'@orig' => __DIR__ . '/../..',
    ],
    
    'modules' => [
        'core'
    ],
    
    'bootstrap' => ['log'],
    'components' => [
        'assetManager' => [
            'basePath' => __DIR__ . '/../../web/assets',
        ],

        'request' => [
            'cookieValidationKey' => sha1(filectime(__FILE__)),
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
            'useFileTransport' => YII_ENV_DEV ? true : false,
            'transport' => [
                'class' => 'Swift_SendmailTransport',
                /*
                'class' => 'Swift_SmtpTransport',
                'host' => 'localhost',
                'username' => '',
                'password' => '',
                'port' => '587',
                'encryption' => 'tls',
                */
            ],
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
        'db' => require(__DIR__ . '/../../config/db.php'),
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    
    'params' => require(__DIR__ . '/../../config/params.php'),
];


if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
