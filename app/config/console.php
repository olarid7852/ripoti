<?php

use yii\queue\beanstalk\Queue;

return [
    'id' => 'yii2_basic_template_console',
    'name' => 'Yii2 Basic Template Console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'myqueue', 'gii'],
    'controllerNamespace' => 'app\commands',
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
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
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
       'db' => require('db.php')
    ],
    'params' => require('params.php'),
    'vendorPath' => dirname(__DIR__) . "/../vendor",
];
