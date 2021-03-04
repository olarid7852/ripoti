<?php

namespace app\assets;

class NavToggleAsset extends AppAsset
{
    public $js = [
        'js/headerNavToggle.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}