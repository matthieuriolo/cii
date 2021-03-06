<?php
$basePath = dirname(__DIR__);
$config = [
    'id' => 'web',
    'basePath' => $basePath,
    'vendorPath' => $basePath . '/modules/cii/vendor',
    'bootstrap' => ['log'],
    'aliases' => [
        '@cii' => $basePath . '/modules/cii/vendor/cii',
        '@core' => '@app/modules/cii',
    ],
    
    'modules' => [
        'cii' => [
            'class' => 'app\modules\cii\Module'
        ],
    ],
    
    'components' => [
        'assetManager' => [
            'forceCopy' => YII_DEBUG
        ],
        
        'cii' => [
            'class' => 'cii\MainComponent'
        ],

        'formatter' => [
            'class' => 'cii\i18n\Formatter'
        ],

        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '_SNqftmezRuI0zppgH3Pp6L_qhRWyiz1',
            'class' => 'cii\web\Request',
        ],

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        'authManager' => [
            'class' => 'cii\rbac\DbManager',
        ],

        'user' => [
            'class' => 'cii\web\User',
            'identityClass' => 'app\modules\cii\models\auth\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['admin/login']
        ],

        'errorHandler' => [
            'errorAction' => 'cii/site/error',
        ],

        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            //'useFileTransport' => YII_ENV_DEV ? true : false,
            
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SendmailTransport',
            ],
        ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logVars' => YII_DEBUG ? null : [],
                ],

                [
                    'class' => 'yii\log\FileTarget',
                    'categories' => ['thumbnail'],
                    'logFile' => '@app/runtime/logs/thumnails.log',
                    'logVars' => [],
                ],

                [
                    'class' => 'yii\log\FileTarget',
                    'categories' => ['mail'],
                    'logFile' => '@app/runtime/logs/mails.log',
                    'logVars' => [],
                ],

                [
                    'class' => 'yii\log\FileTarget',
                    'enabled' => YII_DEBUG,
                    'logFile' => '@runtime/logs/sql.log',
                    'logVars' => [],
                    'levels' => ['profile'],
                    'categories' => ['yii\db\Command::query', 'yii\db\Command::execute'],
                ]
            ],
        ],

        'db' => require(__DIR__ . '/db.php'),
        

        'urlManager' => [
            'class' => 'cii\web\UrlManager',
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    
    'params' => require(__DIR__ . '/params.php'),
];


if(YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    /*$config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];*/

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
