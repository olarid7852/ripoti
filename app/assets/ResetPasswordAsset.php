<?php

namespace app\assets;

class ResetPasswordAsset extends AppAsset
{
    public $js = [
        'js/resetPassword.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}