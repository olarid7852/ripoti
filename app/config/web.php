<?php

use CottaCush\Yii2\Date\DateFormat;
use yii\authclient\clients\Twitter;
use yii\queue\beanstalk\Queue;
use yii\web\JsonParser;

$config = [
    'id' => 'yii2_basic_template',
    'name' => 'Yii2 Basic Template',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'myqueue'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'fwcW1zcfS3R2ks4aiUj28coB2vhWujfl9NGktEnG',
            'class' => 'yii\web\Request',
            'parsers' => [
                'application/json' => JsonParser::class,
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\Admin',
            'enableAutoLogin' => true,
            'enableSession' => true,
            'loginUrl' => ['/admin/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => yii\swiftmailer\Mailer::class,
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => getenv('SMTP_HOST'),
                'username' => getenv('SMTP_USERNAME'),
                'password' => getenv('SMTP_PASSWORD'),
                'port' => getenv('SMTP_PORT'),
                'encryption' => 'tls',
            ],
            'useFileTransport' => false,
        ],
        'myqueue' => [
            'class' => Queue::class,
            'host' => getenv('BEANSTALK_HOST'),
            'port' => getenv('BEANSTALK_PORT'),
            'tube' => 'myqueue',
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'defaultTimeZone' => 'Africa/Lagos',
            'dateFormat' => 'php:' . DateFormat::FORMAT_LONG,
            'datetimeFormat' => 'php:jS M Y g:ia'
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
        'db' => require('db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                'admin/<action:(login|sign-in|logout|forgot-password|send-forgot-password-email|reset-password|reset-sent|success-password|sign-up)>'
                => 'default/<action>',
            ]
        ],
        'permissionManager' => [
            'class' => 'app\components\PermissionManager'
        ],
        'assetManager' => [
            'appendTimestamp' => true,
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => '@npm/jquery/dist',
                    'js' => [
                        YII_ENV_DEV ? 'jquery.js' : '//cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'
                    ]
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'sourcePath' => '@npm/bootstrap/dist',
                    'css' => [
                        YII_ENV_DEV ? 'css/bootstrap.css' :
                            '//maxcdn.bootstrapcdn.com/bootstrap/4.3.0/css/bootstrap.min.css',
                    ]
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'sourcePath' => '@npm/bootstrap/dist',
                    'js' => [
                        YII_ENV_DEV ? 'js/bootstrap.js' :
                            '//maxcdn.bootstrapcdn.com/bootstrap/4.3.0/js/bootstrap.min.js',
                    ]
                ]
            ],
        ],
        'view' => [
            'class' => '\ogheo\htmlcompress\View',
            'compress' => YII_ENV_DEV ? false : true,
        ]
    ],
    'params' => require('params.php'),
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
    ],
    'vendorPath' => dirname(__DIR__) . "/../vendor",
    'aliases' => [
        '@npm' => dirname(__DIR__) . "/../node_modules",
    ]
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
