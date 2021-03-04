<?php

namespace app\controllers;

use app\assets\AssignCaseAsset;
use app\assets\DependentSelectWidgetAsset;
use app\assets\FontAwesomeAsset;
use app\models\forms\CasesTypesForm;
use app\models\Role;
use app\constants\Source;
use app\widgets\DependentSelectWidget;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

/** @var CasesTypesForm $case */
FontAwesomeAsset::register($this);
DependentSelectWidgetAsset::register($this);
AssignCaseAsset::register($this);
$newCase = $case->reportDetails;
$caseSource = $newCase->source;
$caseId = $case->reporter_id;
echo Html::tag('h2', "NEW CASE", ['class' => ' page-header']);
echo Html::beginTag('div', ['class' => 'mt-4 report_data']);
echo Html::tag('p', "<b>Case No : </b>" . 'DRP ' . ($caseId));
if ($caseSource == Source::SOURCE_TWITTER) {
    echo Html::tag('p', "<b>Violation type : </b>" . ucfirst($newCase->violation->names));
}
if ($caseSource == Source::SOURCE_FORM) {
    echo Html::tag('p', "<b>Violation type : </b>" . ucfirst($newCase->violation->names));

}

//FORMS MULTI-SELECT DEPENDENT INPUTS
echo Html::beginTag('div');
$form = ActiveForm::begin([
    'id' => 'case-form',
    'options' => ['class' => 'form-container'],
    'action' => '/case/assign-case',
]);
echo Html::beginTag('div', ['class' => 'row']);
echo Html::beginTag('div', ['class' => 'col case_tabs']);
echo HTML::tag('p', "<b>Assign to:</b> ", ['class' => 'mr-2']);
echo Html::beginTag('div', ['class' => 'control_label col-md-3 pl-0']);
echo DependentSelectWidget::widget([
    'form' => $form,
    'model' => $model,
    'items' => Role::getRoles(),
    'prompt' => 'Select Role',
    'field' => 'assignee_role',
    'classes' => 'select dependent-select-group form-group form-control report-input requiredInput',
    'level' => 0,
    'dependent' => false,
]);
echo Html::endTag('div');
echo Html::beginTag('div', ['class' => 'control_label col-md-3 pl-0']);
echo DependentSelectWidget::widget([
    'form' => $form,
    'model' => $model,
    'items' => [],
    'field' => 'assignee_name',
    'classes' => 'select dependent-select-group form-group form-control report-input requiredInput',
    'level' => 1,
    'parent' => 'assignee_role',
    'dependent' => true,
    'multiple' => true,
    'dataURL' => '/report/admin?adminId=',
]);
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
//SUMMARY TEXT
echo Html::beginTag('div', ['class' => 'case_text_area']);
echo $form->field($model, 'case_id')->hiddenInput(['value' => $caseId])->label(false);
echo $form->field($model, 'id')->hiddenInput(['value' => $case->id])->label(false);
echo $form->field($model, 'violation_type_id')
    ->hiddenInput(['value' => $newCase->violation->names])
    ->label(false);
if ($newCase->source === Source::SOURCE_FORM) {
    echo $form->field($model, 'first_name')
        ->hiddenInput(['value' => $newCase->formReports->first_name])
        ->label(false);
    echo $form->field($model, 'last_name')
        ->hiddenInput(['value' => $newCase->formReports->last_name])
        ->label(false);
}
echo HTML::tag('p', "<b>Summary:</b> ");
echo $form->field($model, 'case_summary')->textarea(
    ['placeholder' => ' ', 'class' => 'reply-text form-control requiredInput', 'id' => 'case-summary']
)->label(false);
//SUMMARY ICONS
echo Html::beginTag('div', ['class' => 'case-icon ml-0 pl-0']);
echo Html::a('ic_attach_file', '#', ['class' => 'material-icons reply-icon']);
echo Html::a('ic_delete', '#', ['class' => 'material-icons reply-icon', 'id' => 'delete-icon']);
echo Html::endTag('div');
echo Html::endTag('div');

echo Html::endTag('div');
//BUTTONS
echo Html::beginTag('div', ['class' => 'incidence_buttons']);
echo Html::a(
    ' CANCEL ',
    '/report/index',
    ['class' => ' btn btn_cancel']
);
echo Html::SubmitButton(
    ' ASSIGN ',
    ['class' => 'btn btn_assign', 'id' => 'assign_btn']
);
ActiveForm::end();

echo Html::endTag('div');
echo Html::endTag('div');
