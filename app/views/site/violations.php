<?php

use yii\bootstrap4\Html;

echo Html::beginTag('div', ['class' => 'd-flex justify-content-center']);
echo Html::tag('p', 'WHAT CAN BE REPORTED?', ['class' => 'mb-4 head_reported']);
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'row mt-4']);
echo Html::beginTag('div', ['class' => 'col-md-6']);
echo Html::beginTag('div', ['class' => 'cards']);
echo Html::img('/images/cyberBulying.png', ['alt' => 'CYBER BULLYING', 'class' => 'img-fluid']);
echo Html::tag('p', 'CYBER BULLYING', ['class' => 'body_reported']);
echo Html::endTag('div');
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'col-md-6']);
echo Html::beginTag('div', ['class' => 'cards']);
echo Html::img('/images/genderViolences.png', ['alt' => 'ONLINE GENDER VIOLENCE', 'class' => 'img-fluid']);
echo Html::tag('p', 'ONLINE GENDER VIOLENCE', ['class' => 'body_reported']);
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'row ']);
echo Html::beginTag('div', ['class' => 'col-md-4 offset-md-2']);
echo Html::beginTag('div', ['class' => 'cards  ']);
echo Html::img('/images/censorships.png', ['alt' => 'INTERNET CENSORSHIP', 'class' => 'img-fluid']);
echo Html::tag('p', 'INTERNET CENSORSHIP', ['class' => 'body_reported text-center']);
echo Html::endTag('div');
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'col-md-4 offset-md-2']);
echo Html::beginTag('div', ['class' => 'cards ']);
echo Html::img('/images/userInformation.png', ['alt' => 'ILLEGAL ACCESS TO USERS INFORMATION', 'class' => 'img-fluid']);
echo Html::tag('p', 'ILLEGAL ACCESS TO USERS INFORMATION', ['class' => 'body_reported text-center']);
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
