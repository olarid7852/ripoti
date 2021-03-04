<?php

namespace app\assets;

class ConfirmCasesReminderAsset extends AppAsset
{
    public $js = [
        'js/confirmCasesReminderModal.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}