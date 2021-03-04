<?php

use app\assets\MaterialDesignIconsAsset;
use app\assets\CreateReportReplyFieldAsset;
use app\assets\ReportViewAsset;
use app\constants\Messages;
use app\constants\Status;
use app\constants\Source;
use app\models\Country;
use app\models\ViolationTypes;
use app\models\forms\ReportForm;
use app\models\forms\ReportTypesForm;
use app\models\twitter\TwitterReport;
use app\models\EmailReport;
use app\models\twitter\TwitterMessage;
use app\models\EmailMessage;
use app\widgets\modals\EditEmailViolationWidget;
use app\widgets\ReportTitleWidget;
use app\widgets\modals\EditReportViolationTypeModal;
use app\widgets\modals\ConfirmStatusUpdateModal;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

ReportViewAsset::register($this);

//HEADER
/** @var ReportTypesForm $report */
/** @var TwitterMessage $reportText */
/** @var EmailMessage $reportText */
echo Html::tag('h2', "INCIDENT REPORT (" . ucfirst($report->source) . ")", ['class' => ' page-header']);
echo ReportTitleWidget::widget(['fileName' => 'Report',
    'file' => 'View',
    'urlName' => '/report/index']);
if ($report->source == Source::SOURCE_FORM) {
    //REPORT DETAILS
    echo Html::beginTag('div', ['class' => 'mt-4 report_data']);
    echo Html::tag('p', "<b>Full Name:</b> " . ucfirst($report->formReports->getFullname()));
    echo Html::tag('p', "<b>Gender:</b> " . ucfirst($report->formReports->gender));
    echo Html::tag('p', "<b>Age:</b> " . ucfirst($report->formReports->age));
    echo Html::tag('p', "<b>Location:</b> " . ucfirst($report->formReports->getLocation()));
    if ($report->formReports->email) {
        echo Html::tag('p', "<b>Email address:</b> " . $report->formReports->email);
    }
    if ($report->formReports->phone_number) {
        echo Html::tag('p', "<b>Phone Number:</b> " . $report->formReports->phone_number);
    }
    echo Html::tag('p', "<b>Violation type:</b> " . ucfirst($report->violation->names));
    echo Html::tag('p', "<b>Date of Violation:</b> " . $report->formReports->occurred_when);
    echo Html::tag('h6', ucfirst($report->formReports->case_subject), ['class' => 'case-subject']);
    echo Html::tag('p', ucfirst($report->formReports->case_description), ['class' => 'case-description']);
} elseif ($report->source == Source::SOURCE_EMAIL) {
    echo Html::tag('p', "<b>Date of Violation:</b> " . $report->emailReports->created_at, [
        'class' => 'mt-4 report_data'
    ]);
    echo Html::tag('p', "<b>Email Address:</b> " . $report->reporterEmail, ['class' => 'mt-4 report_data']);
    if (!$report->emailReports->country_id) {
        echo Html::tag('p', "<b>Location:</b> None", ['class' => 'mt-4 report_data']);
    } else {
        echo Html::tag('p', "<b>Location:</b> " . ucfirst($report->emailReports->getLocation()), [
            'class' => 'mt-4 report_data'
        ]);
    }
    echo Html::a(
        'EDIT REPORT',
        '#',
        [
            'class' => 'btn btn-success edit-violation view',
            'data' => [
                'title' => 'Edit Violation Type',
                'toggle' => 'modal',
                'target' => '#editViolation',
                'url' => Url::toRoute("email-violation?reportId={$report->email_report_id}"),
            ]]
    );
    echo EditEmailViolationWidget::widget([
        'modalId' => 'editViolation',
        'title' => 'Edit Violation Type',
        'classNames' => 'edit-violation-modal',
        'footerCancel' => Messages::ACTION_CANCEL,
        'footerSubmit' => Messages::ACTION_UPDATE,
        'model' => new EmailReport(),
        'formId' => 'edit-form',
        'populateFields' => true,
    ]);
    //CHAT-ROOM FEATURE FOR EMAIL REPORT
    echo Html::beginTag('div', ['class' => 'card chat-room',]);
    echo Html::tag('h5', 'Report Description:', ['class' => 'card-title chat-title']);
    echo Html::beginTag('div', ['class' => 'card-body chat-body']);
    echo Html::beginTag('div', ['class' => 'row']);
    echo Html::beginTag('div', ['class' => 'p-3 col-md-12',]);
    foreach ($reportText as $text) {
        if ($text['sender_id'] == ArrayHelper::getValue(Yii::$app->params, 'adminEmail')) {
            echo Html::beginTag('div', ['class' => 'pb-3 alert twitter-text-message col-md-5 offset-md-7']);
            echo Html::beginTag('div', ['class' => 'admin-message']);
            echo Html::beginTag('div', ['class' => 'message-title']);
            echo Html::tag('sup', 'Ripoti Admin');
            echo Html::tag('sup', $text['timestamp']);
            echo Html::endTag('div');
            echo Html::tag('div', $text['message']);
            echo Html::endTag('div');
            echo Html::endTag('div');
        } else {
            echo Html::beginTag('div', ['class' => 'p-3 alert alert-secondary  col-md-5']);
            echo Html::beginTag('div', ['class' => 'message-title']);
            echo Html::tag('sup', $report->emailReports->reporter_email);
            echo Html::tag('sup', $text['timestamp']);
            echo Html::endTag('div');
            echo Html::tag('div', $text['message']);
            echo Html::endTag('div');
        }
    }
    echo Html::endTag('div');
    echo Html::endTag('div');
    echo Html::endTag('div');
    echo Html::endTag('div');
} elseif ($report->source == Source::SOURCE_TWITTER) {
    echo Html::tag('p', "<b>Date of Violation Report:</b> " . $report->twitterReports->created_at, [
        'class' => 'mt-4 report_data'
    ]);
    echo Html::tag('p', "<b>Twitter handle:</b> " . $report->twitterReports->twitter_handle, [
        'class' => 'mt-4 report_data'
    ]);
    if (!$report->twitterReports->country_id) {
        echo Html::tag('p', "<b>Location:</b>  None", ['class' => 'mt-4 report_data']);
    } else {
        echo Html::tag('p', "<b>Location:</b> " . ucfirst($report->twitterReports->getLocation()), [
            'class' => 'mt-4 report_data'
        ]);
    }
    echo Html::a(
        'EDIT REPORT',
        '#',
        [
            'class' => 'btn btn-success edit-violation view',
            'data' => [
                'title' => 'Edit Violation Type',
                'toggle' => 'modal',
                'target' => '#editModal',
                'url' => Url::toRoute("edit-violation?reportId={$report->twitter_report_id}"),
            ]]
    );
    echo EditReportViolationTypeModal::widget([
        'modalId' => 'editModal',
        'title' => 'Edit Violation Type',
        'classNames' => 'edit-violation-modal',
        'footerCancel' => Messages::ACTION_CANCEL,
        'footerSubmit' => Messages::ACTION_UPDATE,
        'model' => new TwitterReport(),
        'formId' => 'edit-modal-form',
        'populateFields' => true,
    ]);
    //CHAT ROOM FEATURE FOR TWITTER
    echo Html::beginTag('div', ['class' => 'card chat-room',]);
    echo Html::tag('h5', 'Report Description:', ['class' => 'card-title chat-title']);
    echo Html::beginTag('div', ['class' => 'card-body chat-body']);
    echo Html::beginTag('div', ['class' => 'row']);
    echo Html::beginTag('div', ['class' => 'p-3 col-md-12',]);
    foreach ($reportText as $text) {
        if ($text['sender_id'] == getenv('SENDER_ID')) {
            echo Html::beginTag('div', ['class' => 'pb-3 alert twitter-text-message col-md-5 offset-md-7']);
            echo Html::beginTag('div', ['class' => 'admin-message']);
            echo Html::beginTag('div', ['class' => 'message-title']);
            echo Html::tag('sup', 'Ripoti Admin');
            echo Html::tag('sup', $text['timestamp']);
            echo Html::endTag('div');
            echo Html::tag('div', $text['message']);
            echo Html::endTag('div');
            echo Html::endTag('div');
        } else {
            echo Html::beginTag('div', ['class' => 'p-3 alert alert-secondary  col-md-5']);
            echo Html::beginTag('div', ['class' => 'message-title']);
            echo Html::tag('sup', $report->twitterReports->twitter_handle);
            echo Html::tag('sup', $text['timestamp']);
            echo Html::endTag('div');
            echo Html::tag('div', $text['message']);
            echo Html::endTag('div');
        }
    }
    echo Html::endTag('div');
    echo Html::endTag('div');
    echo Html::endTag('div');
    echo Html::endTag('div');
}
//REPORT REPLY SECTION
echo Html::beginTag('div', ['class' => 'report-reply']);
if ($report->source == Source::SOURCE_FORM) {
    echo HTML::tag('p', "<b>Re:</b> " . ucfirst($report->formReports->case_subject));
}
$form = ActiveForm::begin([
    'id' => 'reply-report-form',
    'options' => ['class' => 'form-container'],
]);
if ($report->source == Source::SOURCE_FORM) {
    echo $form->field($model, 'id')->hiddenInput(['value' => $report->formReports->id])->label(false);
    echo $form->field($model, 'email')
        ->hiddenInput(['value' => $report->formReports->email])->label(false);
    echo $form->field($model, 'first_name')
        ->hiddenInput(['value' => $report->formReports->first_name])->label(false);
}
echo $form->field($model, 'report_reply')->textarea(
    ['placeholder' => 'Compose reply', 'class' => 'reply-text form-control requiredInput', 'id' => 'report-text']
)->label(false);
echo Html::beginTag('div', ['class' => 'send-report-reply mb-2']);
if ($report->source == Source::SOURCE_FORM) {
    echo Html::SubmitButton(
        '<i class="fa fa-send" aria-hidden="true"> Send</i>',
        ['class' => 'btn all-report-reply', 'id' => 'form-report-reply']
    );
}
if ($report->source == Source::SOURCE_TWITTER) {
    echo $form->field($model, 'id')->hiddenInput(['value' => $report->twitterReports->id])->label(false);
    echo Html::SubmitButton(
        '<i class="fa fa-send" aria-hidden="true"> Send</i>',
        ['class' => 'btn all-report-reply', 'id' => 'twitter-report-reply']
    );
}
if ($report->source == Source::SOURCE_EMAIL) {
    echo $form->field($model, 'id')->hiddenInput(['value' => $report->emailReports->id])->label(false);
    echo Html::SubmitButton(
        '<i class="fa fa-send" aria-hidden="true"> Send</i>',
        ['class' => 'btn all-report-reply', 'id' => 'email-report-reply']
    );
}
echo Html::a('ic_delete', '#', ['class' => 'material-icons reply-icon mr-5', 'id' => 'delete-icon']);
echo Html::endTag('div');
echo Html::beginTag('div', ['class' => 'report-icon']);
$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
echo $form->field($model, 'attach')->fileInput(['options' => ['class' => 'materials-icons reply-icon']])
    ->label(false);
ActiveForm::end();
echo Html::endTag('div');
ActiveForm::end();
echo Html::endTag('div');
//BUTTONS
echo Html::beginTag('div', ['class' => 'incidence_buttons']);
echo Html::a(
    '<i class="fa fa-caret-left" aria-hidden="true"> BACK</i>',
    '/report/index',
    ['class' => ' btn btn_back']
);
echo Html::a(
    '<i class="fa fa-reply" aria-hidden="true"> REPLY</i>',
    '#',
    ['class' => 'btn btn_reply ', 'id' => "reply-report"]
);

if ($report->status == Status::STATUS_VERIFIED) {
    echo Html::a(
        '<i class="fa fa-camera" aria-hidden="true"> CREATE CASE</i>',
        '#',
        [
            'class' => 'edit-status btn btn_create',
            'data' => [
                'title' => '',
                'toggle' => 'modal',
                'target' => '#editStatus',
                'url' => Url::toRoute("create-case?reportId={$report->id}"),
                'status' => Status::STATUS_UNVERIFIED,
                'msg' => Messages::getWarningMessage('Report', 'make a case for ')
            ]]
    );
}
if ($report->status == Status::STATUS_UNVERIFIED) {
    echo Html::a(
        '<i class="fa fa-check" aria-hidden="true"> VERIFY</i>',
        '#',
        [
            'class' => 'edit-status btn btn_create',
            'data' => [
                'title' => 'Verify Report',
                'toggle' => 'modal',
                'target' => '#editStatus',
                'url' => Url::toRoute("update-report-status?reportId={$report->id}"),
                'status' => Status::STATUS_VERIFIED,
                'msg' => Messages::getWarningMessage('Report', 'verify')
            ]]
    );
}
echo Html::endTag('div');

//MODAL FOR VERIFICATION
echo ConfirmStatusUpdateModal::widget([
    'modalId' => 'editStatus',
    'footerCancel' => Messages::NO,
    'footerSubmit' => Messages::YES,
    'model' => new ReportTypesForm(),
]);
