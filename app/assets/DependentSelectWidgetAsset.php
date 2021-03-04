<?php

namespace app\assets;

use yii\web\AssetBundle;

class DependentSelectWidgetAsset extends AssetBundle
{
    public $js = [
        'js/dependentSelect.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}