<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

echo Html::beginTag('div', ['class' => 'row ']);
echo Html::beginTag('div', ['class' => 'mx-auto']);
echo Html::beginTag('div', ['class' => 'col-lg-8 col-sm-10 col-md-7 mx-auto']);
echo Html::beginTag('div', ['class' => ' case justify-content-center']);
echo Html::img('/images/logo.jpg', ['alt' => 'logo', 'class' => 'img-fluid logo-center']);
echo Html::tag('h3', 'Forgot Password?', ['class'=>'forgot']);
echo Html::tag('p', 'Please enter your email address associated with your account', ['class' => 'graph']);
echo Html::tag('p', 'We will email you a link to reset your password', ['class' => 'link-text']);
//EMAIL FIELD
echo Html::beginTag('div', ['class' => 'container']);
echo Html::beginTag('div', ['class' => 'row']);
echo Html::beginTag('div', ['class' => 'col-lg-10 col-sm-10 col-md-7 mx-auto']);
$form = ActiveForm::begin([
     'id' => 'forgot-password-form',
    'action' => 'send-forgot-password-email',
    'options' => ['class' => 'input-email']
]);
echo Html::beginTag('div', ['class' => 'form-group']);
echo $form->field($model, 'email')->textInput(['class'=>'custom- select form-control input-emails', 'placeholder' => 'Enter Email Address']);
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::beginTag('div', ['class' => 'row']);
echo Html::beginTag('div', ['class' => 'mx-auto']);
echo Html::submitButton('SEND ', ['class' => 'sents', 'name' => 'send-button']);
echo Html::endTag('div');
echo Html::endTag('div');
ActiveForm::end();
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');