<?php

use yii\helpers\Html;

echo Html::beginTag('div', ['class' => 'row']);
echo Html::beginTag('div', ['class' => 'green-box mx-auto']);
echo Html::beginTag('div', ['class' => 'col-lg-10 mx-auto']);
echo Html::beginTag('div', ['class' => 'pt-4 text-center']);
echo Html::tag('p', 'Get in touch', ['class' => 'm-0 contact_head']);
echo Html::tag(
    'p',
    "We'd really like to hear from you, here are the following ways to reach us",
    ['class' => 'mb-4 subhead-contact']);
echo Html::endTag('div');
//ADDRESSES
echo Html::beginTag('div', ['class' => 'row']);
echo Html::beginTag('div', ['class' => 'col-md-6']);
echo Html::beginTag('div', ['class' => 'card contacts-card']);
echo Html::beginTag('div', ['class' => 'card-body']);
echo Html::beginTag('div', ['class' => 'card-text contact-news']);
echo Html::tag('span', 'mail', ['class' => 'material-icons contact-icon']);
echo Html::tag('p', 'Hello@ripoti.africa', ['class' => 'contact-number']);
echo Html::tag('span', 'call', ['class' => 'material-icons contact-icon']);
echo Html::tag('p', '<b>Please contact us via these numbers :</b>', ['class' => 'contact-number']);
echo Html::tag('h', '+234 1342 6245', ['class' => 'contact-number']);
echo Html::tag('p', '+234 9291 6301', ['class' => 'contact-number']);
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
//SOCIAL HANDLES
echo Html::beginTag('div', ['class' => 'col-md-6']);
echo Html::beginTag('div', ['class' => 'card contacts-card']);
echo Html::beginTag('div', ['class' => 'card-body']);
echo Html::beginTag('div', ['class' => 'card-text']);
echo Html::tag('p', '<b>Or better still through social media handles</b>', ['class' => 'contact-number']);
echo Html::beginTag('div', ['class' => 'socials']);
echo Html::tag(
    'div',
    '<i class="fa fa-twitter mr-2"></i><span class="social-links">@RipotiAfrica</span>',
    ['class' => 'contact-icon']
);
echo Html::endTag('div');
echo Html::beginTag('div', ['class' => 'socials']);
echo Html::tag(
    'div',
    '<i class="fa fa-facebook-official mr-2"></i><span class="social-links">facebook.com</span>',
    ['class' => 'contact-icon']
);
echo Html::endTag('div');
echo Html::beginTag('div', ['class' => 'socials']);
echo Html::tag(
    'div',
    '<i class="fa fa-instagram mr-2"></i><span class="social-links">instagram.com</span>',
    ['class' => 'contact-icon']
);
echo Html::endTag('div');
echo Html::endTag('div');

echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
