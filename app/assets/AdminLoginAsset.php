<?php

namespace app\assets;

class AdminLoginAsset extends AppAsset
{
    public $js = [
        'js/adminLogin.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}