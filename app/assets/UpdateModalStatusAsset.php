<?php

namespace app\assets;

class UpdateModalStatusAsset extends AppAsset
{
    public $js = [
        'js/updateModalStatus.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}