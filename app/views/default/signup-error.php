<?php

use yii\helpers\Html;

echo Html::beginTag('div', ['class' => 'text-center mt-5']);
echo Html::tag(
    'p',
    '<strong>An error occurred while creating your profile, Please try again or contact admin at</strong>'
);
echo Html::mailto('digitalrghts@putsbox.com ');
echo Html::tag('span', '<strong> if error repeats.</strong>');
echo Html::endTag('div');
