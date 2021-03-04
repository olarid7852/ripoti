<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use app\assets\ResetPasswordAsset;

ResetPasswordAsset::register($this);

echo Html::beginTag('div', ['class' => 'container-fluid']);
echo Html::beginTag('div', ['class' => 'row']);
echo Html::beginTag('div', ['class' => 'col-lg-4 col-md-6 mx-auto']);
echo Html::beginTag('div', ['class' => ' card_body']);
echo Html::beginTag('div', ['class' => ' rule-image mx-auto']);
echo Html::img('/images/logo.jpg', ['class' => 'img-fluid reset-logo']);
echo Html::endTag('div');
echo Html::beginTag('div', ['class' => 'row']);
echo Html::tag('h3', 'RESET PASSWORD', ['class'=> 'heads mx-auto']);
echo Html::endTag('div');
echo Html::beginTag('div', ['class' => 'row']);
echo Html::beginTag('div', ['class' => 'col-lg-9 col-md-10 col-xs-10 mx-auto']);
echo Html::tag('h4', 'Please enter a new password for your account below ', ['class'=>'heading']);
echo Html::beginTag('div', ['class' => 'form-group reset-form']);
$form = ActiveForm::begin([
    'action'=> ['save-new-password', 'method' => 'POST'],
    'fieldConfig' => [
    'template' => "<div class=\" col-lg-12 col-xs-6\"> {label}</div>{input}
    <div class=\"col-lg-offset-1 col-lg-12 col-xs-6\"> {error}</div>",
        'labelOptions' => ['class' => 'label'],
    ],
]);
echo $form->field($model, 'user_id')->hiddenInput()->label(false);
echo $form->field($model, 'password')->passwordInput(['class'=>'form-control form-input','id'=>'input-pwd' ]);
echo Html::beginTag('i', ['class' => 'fa fa-fw fa-eye field-icon toggle-password']);
echo Html::endTag('i');
echo Html::beginTag('div', ['class' => 'form-group reset-form']);
echo $form->field($model, 'confirm_password')->passwordInput(
    ['class'=>'form-control form-input', 'id'=>'confirm-pwd']
);
echo Html::beginTag('i', ['class' => 'fa fa-fw fa-eye field-icon toggle-confirm-password']);
echo Html::endTag('i');
echo Html::endTag('div');
echo Html::Submitbutton('Reset', ['class' => 'resets  btn-lg']);
ActiveForm::end();
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
