<?php

use app\assets\ReportFormAsset;
use app\assets\DependentSelectWidgetAsset;
use app\constants\StaticContent;
use app\models\Country;
use app\models\forms\ReportForm;
use app\models\ViolationTypes;
use app\widgets\DatePickerWidget;
use app\widgets\DependentSelectWidget;
use app\widgets\FooterWidget;
use app\widgets\NavbarWidget;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

DependentSelectWidgetAsset::register($this);
ReportFormAsset::register($this);

//REPORT HEADLINE

echo Html::beginTag('div', ['class' => 'report-form', 'id' => 'report-form1']);
echo Html::tag('h2', 'REPORT FORM'. '<hr>', ['class' => 'text-center form-title']);
echo Html::tag('p', StaticContent::REPORT_FORM_PARAGRAPH, ['class' => 'text-center p-text']);
$form = ActiveForm::begin([
    'id' => 'report-form',
    'options' => ['class' => 'form-container'],
    'action' => 'create-report-form',
]);

echo Html::beginTag('div', ['class' => 'form-group row justify-content-center']);
echo Html::beginTag('div', ['class' => 'form-inline']);
echo $form->field($model, 'reporting_as')->dropDownList(ReportForm::$reporterType, [
    'prompt' => 'Please select one',
    'class' => 'form-control report-input mustFill'
]);
echo Html::endTag('div');
echo Html::endTag('div');

//VIOLATION DETAILS
echo Html::tag('span', 'Violation Details');
echo Html::tag('hr', ['class' => 'hr']);
echo Html::beginTag('div', ['class' => 'row']);
echo Html::beginTag('div', ['class' => 'col-sm-8 col-xs-12']);
echo $form->field($model, 'violation_type_id')
    ->dropDownList(ViolationTypes::getViolationTypes(), [
        'prompt' => 'Please select type',
        'class' => 'form-control report-input mustFill'
    ]);
echo Html::endTag('div');
echo Html::beginTag('div', ['class' => 'col-sm-4 col-xs-12']);
echo Html::beginTag('div', ['class' => 'form-group required']);
echo Html::tag('label', 'When did it occur?');
echo DatePickerWidget::widget([
    'form' => $form,
    'model' => $model,
    'attribute' => 'occurred_when',
    'class' => 'form-group form-control report-input mustFill',
]);
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');

//REPORT CASE SUBJECT AND DESCRIPTION
echo Html::tag('span', 'Please provide more details about your report');
echo Html::tag('hr', ['class' => 'hr']);
echo Html::beginTag('div', ['class' => 'row']);
echo Html::beginTag('div', ['class' => 'col-12']);
echo $form->field($model, 'case_subject')->textInput(['class' => 'mustFill form-control report-input']);
echo Html::endTag('div');
echo Html::beginTag('div', ['class' => 'col-12']);
echo $form->field($model, 'case_description')->textarea([
    'placeholder' => 'Please enter a description of your digital rights violation',
    'class' => 'form-control report-textarea mustFill',
]);
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::button('NEXT', ['class' => 'btn btn-primary btn-submit-form pull-right', 'id' => 'next_btn']);
echo Html::endTag('div');
echo Html::tag('div', '<br>');

//ADDITIONAL INFORMATION
echo Html::beginTag('div', ['id' => 'additional-report-form', 'class' => 'report-form']);
echo Html::tag('h2', 'Additional Information'. '<hr>', ['class' => 'form-title text-center']);
echo Html::tag('p', StaticContent::ADDITIONAL_INFORMATION_PARAGRAPH, ['class' => 'text-center p-text']);
echo Html::tag('span', 'Where are you reporting from?');
echo Html::tag('hr', ['class' => 'hr']);

//COUNTRY AND STATE LOCATION
echo Html::beginTag('div', ['class' => 'row']);
echo Html::beginTag('div', ['class' => 'col-lg-6 col-xs-12']);
echo DependentSelectWidget::widget([
    'form' => $form,
    'model' => $model,
    'items' => Country::getCountry(),
    'field' => 'country_id',
    'prompt' => 'Please select one',
    'classes' => 'select dependent-select-group form-group form-control report-input requiredField',
    'level' => 0,
    'label' => 'Country'
]);
echo Html::endTag('div');
echo Html::beginTag('div', ['class' => 'col-lg-6 col-xs-12']);
echo DependentSelectWidget::widget([
    'form' => $form,
    'model' => $model,
    'items' => [],
    'prompt' => 'Please select one',
    'field' => 'state_id',
    'classes' => 'select dependent-select-group form-group form-control report-input requiredField',
    'level' => 1,
    'parent' => 'country_id',
    'dependent' => true,
    'dataURL' => '/site/states?countryId=',
    'label' => 'State'
]);
echo Html::endTag('div');
echo Html::endTag('div');

//CREDENTIALS
echo Html::tag('span', 'Please supply your credentials');
echo Html::tag('hr', ['class' => 'hr']);
echo Html::beginTag('div', ['class' => 'row']);
echo Html::beginTag('div', ['class' => 'col-lg-6 col-xs-12']);
echo $form->field($model, 'gender')->dropDownList(ReportForm::$genderType, [
    'prompt' => 'Please select gender',
    'class' => 'form-control report-input'
]);
echo Html::endTag('div');
echo Html::beginTag('div', ['class' => 'col-lg-6 col-xs-12']);
echo $form->field($model, 'contact')->dropDownList(ReportForm::$contactType, [
    'prompt' => 'Please select type',
    'class' => 'form-control report-input requiredField'
]);
echo Html::endTag('div');
echo Html::endTag('div');

//DETAILS
echo Html::beginTag('div', ['class' => 'row']);
echo Html::beginTag('div', ['class' => 'col-lg-6 col-xs-12']);
echo $form->field($model, 'first_name')->textInput(['class' => 'form-control report-input requiredField']);
echo Html::endTag('div');
echo Html::beginTag('div', ['class' => 'col-lg-6 col-xs-12']);
echo $form->field($model, 'last_name')->textInput(['class' => 'form-control report-input requiredField']);
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::beginTag('div', ['class' => 'row']);
echo Html::beginTag('div', ['class' => 'col-lg-6 col-xs-12']);
echo $form->field($model, 'age')->dropDownList(ReportForm::$ageRange, [
    'prompt' => 'Please select one',
    'class' => 'form-control report-input'
]);
echo Html::endTag('div');
echo Html::endTag('div');

//PHONE CONTACT
echo Html::beginTag('div', ['class' => 'row']);
echo Html::beginTag('div', ['class' => 'col-lg-6 col-xs-12 contact', 'id' => 'phone-contact']);
echo $form->field($model, 'phone_number')->textInput(['class' => 'form-control report-input', 'type' => 'number']);
echo Html::endTag('div');
echo Html::endTag('div');

//EMAIL CONTACT
echo Html::beginTag('div', ['class' => 'row']);
echo Html::beginTag('div', ['class' => 'col-lg-8 col-xs-12 contact', 'id' => 'email-contact']);
echo $form->field($model, 'email')->textInput(['class' => 'form-control report-input']);
echo Html::endTag('div');
echo Html::endTag('div');

//OTHER CONTACT
echo Html::beginTag('div', ['class' => 'row']);
echo Html::beginTag('div', ['class' => 'col-lg-8 col-xs-12 contact', 'id' => 'other-contact']);
echo $form->field($model, 'other_means_of_contact')->textInput(['class' => 'form-control report-input']);
echo Html::endTag('div');
echo Html::endTag('div');
echo $form->field($model, 'source')->hiddenInput(['value' => 'form'])->label(false);

//DATA CONSENT CHECKBOX
echo Html::beginTag('div', ['class' => 'checkbox form-group form-check']);
echo $form->field($model, 'data_consent')->checkbox()
    ->label(
        '' .
        Html::tag('span', StaticContent::DATA_USE_CONSENT, ['class' => 'checkmark'])
    );
echo Html::endTag('div');
//CONTROL BUTTONS
echo Html::beginTag('div', ['class' => 'row control-buttons justify-content-center']);
echo Html::button('BACK', ['class' => 'btn btn-danger btn-back', 'id' => 'back_btn']);
echo Html::SubmitButton('SUBMIT', ['class' => 'btn-success btn btn-submit-form', 'id' => 'submit_btn']);
echo Html::endTag('div');
echo Html::endTag('div');
ActiveForm::end();