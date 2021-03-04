<?php

namespace app\widgets\modals;

use app\assets\ConfirmCasesReminderAsset;
use yii\bootstrap4\Html;

class ConfirmCasesReminderModal extends ConfirmStatusUpdateModal
{
    public function renderContents()
    {
        ConfirmCasesReminderAsset::register($this->view);
        echo Html::tag('p', $this->message, ['class' => '', 'data-msg' => is_null($this->message)]);
    }
}