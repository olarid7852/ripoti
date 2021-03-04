<?php

use app\widgets\FooterWidget;
use app\widgets\NavbarWidget;
use app\constants\Site;
use yii\bootstrap4\Html;

echo Html::beginTag('div', ['class' => 'row justify-content-center']);
echo Html::beginTag('div', ['class' => 'col-12 landing-info']);
echo Html::img('/images/index-logo.jpg', ['alt' => 'logo']);
echo Html::beginTag('div', ['class' => 'bold']);
echo Html::tag('p', Site::ABOUT_US);
echo Html::endTag('div');
echo Html::beginTag('div', ['class' => 'normal']);
echo Html::tag('p', Site::REPORT_PRIVACY);
echo Html::a('MAKE A REPORT', '/site/report-form', ['class' => 'report-button btn']);
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');