<?php

use app\assets\CaseViewAsset;
use app\assets\ReportViewAsset;
use app\assets\SlimScrollAsset;
use app\constants\Messages;
use app\models\cases\CaseAssignment;
use app\models\cases\CaseMessages;
use app\widgets\ReportTitleWidget;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use app\models\forms\CasesTypesForm;
use yii\helpers\Url;

/** @var CasesTypesForm $case */
/** @var CaseAssignment $assignee */
/** @var CaseAssignment $adminId */
/** @var CaseAssignment $adminName */
/** @var CaseMessages $caseText */
CaseViewAsset::register($this);
echo Html::Tag('h3', 'VIEW CASE', ['class' => ' page-header']);
echo ReportTitleWidget::widget(['fileName' => 'Cases', 'urlName' => 'index', 'file' => 'View']);
echo Html::beginTag('div', ['class' => 'mt-4 report_data']);
echo Html::tag('p', "<b>Case No :</b> $case->case_id");
echo Html::tag('p', "<b>Violation type :</b> " . ucfirst($case->reportDetails->violation->names));
echo Html::tag('p', "<b>Status :</b> " . strtoupper($case->status));
echo Html::tag('p', "<b>Assigned to :</b> " . $assignee);

//WITHDRAW A CASE ASSIGNEE
echo Html::beginTag('div', ['class' => 'withdrawCase mb-5']);
$form = ActiveForm::begin([
    'id' => 'withdraw-case-form',
    'options' => ['class' => 'form-container', 'name' => 'withdraw-case-form'],
    'action' => 'withdraw-case',
]);
echo $form->field($model, 'case_id')->dropDownList(CaseAssignment::getAssignees($case->id), [
    'prompt' => 'Please select assignee',
    'class' => 'form-control custom-select required'
])->label(false);
echo $form->field($model, 'id')->hiddenInput(['value' => $case->id])->label(false);
echo Html::SubmitButton(
    ' WITHDRAW ASSIGNEE',
    ['class' => 'btn btn-danger', 'id' => 'withdraw-case']
);
ActiveForm::end();
echo Html::endTag('div');

//CHAT ROOM
echo Html::beginTag('div', ['class' => 'card chat-room',]);
echo Html::tag('h5', 'Case Summary:', ['class' => 'card-title chat-title']);
echo Html::beginTag('div', ['class' => 'card-body chat-body']);
echo Html::beginTag('div', ['class' => 'row']);
echo Html::beginTag('div', ['class' => 'p-3 col-md-12',]);
foreach ($caseText as $text) {
    if ($text['sender_id'] == $adminId) {
        echo Html::beginTag('div', ['class' => 'p-3 alert twitter-text-message col-md-5 offset-md-7']);
        echo Html::beginTag('div', ['class' => 'admin-message']);
        echo Html::beginTag('div', ['class' => 'message-title']);
        echo Html::tag('sup', $adminName);
        echo Html::tag('sup', $text['created_at']);
        echo Html::endTag('div');
        echo Html::tag('div', $text['case_messages']);
        echo Html::endTag('div');
        echo Html::endTag('div');
    } else {
        echo Html::beginTag('div', ['class' => 'p-3 alert alert-secondary actor-message col-md-5']);
        echo Html::beginTag('div', ['class' => 'message-title']);
        echo Html::tag('sup', $text->caseDetails->adminDetails->fullName);
        echo Html::tag('sup', $text['created_at']);
        echo Html::endTag('div');
        echo Html::tag('div', $text['case_messages']);
        echo Html::endTag('div');
    }
}
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');

//Case reply section
echo Html::beginTag('div', ['class' => 'cases-reply']);
echo HTML::tag('p', "<b>Re:</b> ");
$form = ActiveForm::begin([
    'id' => 'reply-case-form',
    'options' => ['class' => 'form-container'],
    'action' => 'admin-case-reply',
]);
echo $form->field($model, 'reply_case')->textarea(
    ['placeholder' => 'Compose reply', 'class' => 'reply-text form-control']
)->label(false);
echo $form->field($model, 'id')->hiddenInput(['value' => $case->id])->label(false);
echo Html::beginTag('div', ['class' => 'send-report-reply']);
echo Html::SubmitButton(
    '<i class="fa fa-send" aria-hidden="true"> Send</i>',
    ['class' => 'btn btn-success', 'id' => 'send-report-reply']
);
echo Html::endTag('div');
echo Html::beginTag('div', ['class' => 'report-icon']);
echo Html::a('ic_attach_file', '#', ['class' => 'material-icons reply-icon']);
echo Html::a('ic_delete', '#', ['class' => 'material-icons reply-icon', 'id' => 'delete-icon']);
echo Html::endTag('div');
ActiveForm::end();
echo Html::endTag('div');
echo Html::endTag('div');
//BUTTONS
echo Html::beginTag('div', ['class' => 'incidence_buttons']);
echo Html::a(
    '<i class="fa fa-caret-left" aria-hidden="true"> BACK</i>',
    '/case/index',
    ['class' => ' btn btn_back']
);
echo Html::a(
    '<i class="fa fa-reply" aria-hidden="true"> REPLY</i>',
    '#',
    ['class' => 'btn btn_reply ', 'id' => "cases-reply"]
);
echo Html::a(
    '<i class="fa fa-chain-broken" aria-hidden="true"> WITHDRAW CASE</i>',
    '#',
    ['class' => 'edit-violation btn btn_create', 'id' => 'case_withdraw']
);
echo Html::endTag('div');
