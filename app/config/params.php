<?php
/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */

return [
    'adminEmail' => 'drvp@ripoti.africa',
    'emailSenderName' => 'Ripoti',
    'baseUrl' => getenv('BASE_URL'),
    'excludedPaths' => [
        'default/login',
        'default/sign-in',
        'default/signup-error',
        'default/forgot-password',
        'default/reset-password',
        'default/reset-sent',
        'default/success-password',
        'default/sign-up',
        'site/create-report-form',
        'site/report-form',
        'site/index',
        'site/report-success',
        'case/view-case',
        'site/additional-information',
        'site/about',
        'site/states',
        'site/contact',
        'site/violations',
        'site/cases',
        'site/contact',
        'twitter',
        'twitter/webhook'
    ],

    'defaultCacheTimeout' => 3600,
];
