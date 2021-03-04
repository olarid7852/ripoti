<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;

echo Html::beginTag('div', ['class' => 'container']);
echo Html::beginTag('div', ['class' => ' row justify-content-center']);
echo Html::beginTag('div', ['class' => 'col-lg-5 col-md-6 sign_up_page']);
echo Html::beginTag('div', ['class' => ' text-center']);
echo Html::img('/images/logo.jpg', ['class' => 'line-image img-fluid']);
echo Html::tag('h3', 'ACCEPT INVITATION', ['class' => 'accept_invite']);
echo Html::tag('h4', 'Please set up your account profile ', ['class' => 'sign_up_head']);
echo Html::endTag('div');
//Begin form
echo Html::beginTag('div', ['class' => 'col']);
echo Html::beginTag('div', ['class' => 'form-group sign_up_form']);
$form = ActiveForm::begin([
    'options' => ['class' => ' '],
    'action' => Url::toRoute('/users/sign-up-user'),
]);

echo $form->field($model, 'email')->hiddenInput()->label(false);
echo $form->field($model, 'role_key')->hiddenInput()->label(false);
echo $form->field($model, 'token')->hiddenInput()->label(false);
echo Html::beginTag('div', ['class' => 'form-group sign_up_form']);
echo $form->field($model, 'first_name')->textInput(
    ['class' => ' form-control sign_up_label']
);
echo Html::endTag('div');
echo Html::beginTag('div', ['class' => 'form-group sign_up_form']);
echo $form->field($model, 'last_name')->textInput(
    ['class' => 'form-control sign_up_label']
);
echo Html::endTag('div');
echo Html::beginTag('div', ['class' => 'form-group sign_up_form']);
echo $form->field($model, 'password')->passwordInput(
    ['class' => 'form-control sign_up_label']
);
echo Html::endTag('div');
echo Html::beginTag('div', ['class' => 'form-group sign_up_form']);
echo $form->field($model, 'confirm_password')->passwordInput(
    ['class' => 'form-control sign_up_label']
);
echo Html::endTag('div');
echo Html::beginTag('div', ['class' => 'text-center ']);
echo Html::Submitbutton('Create Profile', ['class' => 'button_sign_up btn ']);
echo Html::endTag('div');
ActiveForm::end();
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
