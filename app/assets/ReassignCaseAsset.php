<?php
namespace app\assets;
class ReassignCaseAsset extends AppAsset
{
    public $js = [
        'js/reassignCase.js',
        'js/reassignUser.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}