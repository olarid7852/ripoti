<?php

use yii\helpers\Html;

echo Html::beginTag('div', ['class' => 'logo']);
echo Html::img('/images/logo.jpg', ['alt' => 'logo','class'=>'img-fluid logo1']);
echo Html::endTag('div');
echo Html::beginTag('div', ['class' => 'container']);
echo Html::beginTag('div', ['class' => 'row justify-content-center']);
echo Html::beginTag('div', ['class' => 'col-lg-6 col-md-8 mt-4']);
echo Html::beginTag('div', ['class' => ' mx-auto ']);
echo Html::beginTag('div', ['class' => 'media']);
echo Html::beginTag('div', ['class' => 'media-middle mx-auto']);
echo Html::img('/images/phone.png', ['class' => 'media-object iconi']);
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::tag( 
'p', 'We have sent you a password reset link in the address attached to this account', ['class' => 'card-text prg ']);
echo Html::beginTag('div', ['class' => 'email_link col-lg-12']);
echo Html::tag('p', "If you didn't receive the link in your email address kindly ", ['class' => 'sent']);
echo Html::a('click here', ['forgot-password'], ['class'=>'link3']);
echo Html::tag('p', " to resend", ['class'=>'sent']);
echo Html::endTag('div');
echo Html::beginTag('div', ['class' => 'row']);
echo Html::beginTag('div', ['class' => 'mx-auto ']);
echo Html::a('OK', 'login', ['class' => 'btn ok ']);
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
