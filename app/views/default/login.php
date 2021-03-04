<?php 

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use app\assets\AdminLoginAsset;
use app\assets\FontAwesomeAsset;

AdminLoginAsset::register($this);
FontAwesomeAsset::register($this);

// ADMIN LOGIN FORM 
echo Html::beginTag('div', ['class' => 'container main']);
echo Html::beginTag('div', ['class' => 'row justify-content-center']);
echo Html::img('/images/logo.jpg', ['alt' => 'My logo', 'class'=>'imga img-fluid']);
echo Html::beginTag('div', ['class' => 'container']);
echo Html::beginTag('div', ['class' => 'col-lg-5 col-sm-6 col-md-6 login-form mx-auto' ]);
echo HTML::tag('h2', 'SIGN IN', ['class' => 'header-2']);
echo HTML::tag('P', 'Please enter your credentials', ['class' => 'paragraph']);
// FORM
$form = ActiveForm::begin([
    'id' => 'login-form',
    'options' => ['class' => 'form-container'],
    'action' => 'sign-in'
]);
echo Html::beginTag('div', ['class' => 'form-group input-group']);
echo $form->field($model, 'email')->textInput(['maxlength' => 255, 'class' => 'form-control input']);
echo Html::endTag('div');
echo Html::beginTag('div', ['class' => 'form-group input-group']);
echo $form->field($model, 'password')
    ->passwordInput(['class' => 'form-control input', 'id' => 'password-toggle']);
echo Html::tag('i', '', ['class' => 'fa fa-eye-slash icon', 'data-toggle' => '#password-toggle']);
echo Html::endTag('div');
// // FORGOT PASSWORD SECTION
echo Html::beginTag('div', ['class' => 'forgot-section']);
echo Html::beginTag('div', ['class' => 'checkbox form-group form-check']);
echo $form->field($model, 'rememberMe')->checkbox()
    ->label(
        'Remember me' .
        Html::tag('span', '', ['class' => 'checkmark'])
    );
echo Html::endTag('div');
echo Html::beginTag('div');
echo Html::a('Forgot password?', 'forgot-password', ['class' => 'anchor']);
echo Html::endTag('div');
echo Html::endTag('div');
// END OF FORGOT PASSWORD SECTION
echo Html::beginTag('div', ['class' => 'form-group input-group']);
echo Html::submitButton(
    'LOG IN',
    ['class' => 'button btn btn-block', 'name'=>'login-button']
);
echo Html::endTag('div');
ActiveForm::end();
// FORM END
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
