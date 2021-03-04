<?php

use yii\helpers\Html;

echo Html::beginTag('div', ['class'=> 'container']);
echo Html::img('/images/logo.jpg', ['class' => 'logo1 img-fluid']);
echo Html::beginTag('div', ['class'=> 'row']);
echo Html::beginTag('div', ['class'=> 'col-lg-8 col-md-8 col-xs-10 mx-auto']);
echo Html::beginTag('div', ['class'=> 'files']);
echo Html::beginTag('div', ['class'=> 'file']);
echo Html::img('/images/check.png', ['class' => 'check img-fluid']);
echo Html::tag('h3', 'Congratulations!', ['class'=>'congrat']);
echo Html::endTag('div');
echo Html::tag('p', "You've successfully changed your password", ['class' => 'para mx-auto']);
echo Html::a('Proceed to SIGN IN', 'login', ['class'=>'button ', 'data-method'=>'POST']);
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
