<?php


namespace app\assets;


class CaseViewAsset extends AppAsset
{
    public $js = [
        'js/caseView.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}