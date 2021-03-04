<?php

namespace app\assets;

class ReportViewAsset extends AppAsset
{
    public $js = [
        'js/reportView.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}