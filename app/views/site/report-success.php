<?php

use app\widgets\FooterWidget;
use app\widgets\NavbarWidget;
use yii\bootstrap\Html;

echo Html::beginTag('div', ['class' => 'row justify-content-center align-items-center']);
echo Html::beginTag('div', ['class' => 'col-10, message-wrapper']);
echo Html::beginTag('div');
echo Html::beginTag('div', ['class' => 'success-image']);
echo Html::img('/images/success-report.png', ['alt' => 'success-logo', 'class' => 'center']);
echo Html::endTag('div');
echo Html::beginTag('div', ['class' => 'success-text']);
echo Html::tag('h1', 'Done!');
echo Html::tag('p', 'Your report has been submitted', ['class' => 'success-text-bold']);
echo Html::tag('p', 'You will be contacted for any other details regarding your report');
echo Html::beginTag('div', ['class' => 'link-text']);
echo Html::a('Return to Report page ', 'index');
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');