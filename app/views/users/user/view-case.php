<?php

use app\assets\CaseViewAsset;
use app\constants\Messages;
use app\constants\Status;
use app\models\cases\CaseAssignment;
use app\widgets\modals\ConfirmCasesReminderModal;
use app\widgets\ReportTitleWidget;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\Url;

CaseViewAsset::register($this);

echo Html::Tag('h3', 'CASE VIEW', ['class' => ' page-header']);
echo ReportTitleWidget::widget([
    'fileName' => 'Cases',
    'file' => 'View',
    'urlName' => '/case/index',
]);

/**@var CaseAssignment $case * */
/**@var CaseAssignment $admin * */
/**@var CaseAssignment $adminName * */
/**@var CaseAssignment $caseText * */
echo Html::beginTag('div', ['class' => 'mt-4 report_data']);
echo Html::tag('p', "<b>Case No :</b>" . $case->caseDetails->case_id);
echo Html::tag('p', "<b>Violation type :</b> " . $case->caseDetails->reportDetails->violation->names);
echo Html::tag('p', "<b>Status :</b> " . str_replace('-', ' ', strtoupper($case->status)));
echo Html::endTag('div');
//CHAT ROOM
echo Html::beginTag('div', ['class' => 'card chat-room',]);
echo Html::tag('h5', 'Case Summary:', ['class' => 'card-title chat-title']);
echo Html::beginTag('div', ['class' => 'card-body chat-body']);
echo Html::beginTag('div', ['class' => 'row']);
echo Html::beginTag('div', ['class' => 'p-3 col-md-12',]);
foreach ($caseText as $text) {
    if ($text['sender_id'] == $admin) {
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
        echo Html::beginTag('div', ['class' => 'p-3 alert alert-secondary  col-md-5']);
        echo Html::beginTag('div', ['class' => 'message-title']);
        if ($text['sender_id'] === 1) {
            echo Html::tag('sup', 'Ripoti');
        } else {
            echo Html::tag('sup', $text->caseDetails->adminDetails->fullName);
        }
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
//REPLY SECTION
echo Html::beginTag('div', ['class' => 'cases-reply']);
echo HTML::tag('p', "<b>Re:</b> ");
$form = ActiveForm::begin([
    'id' => 'reply-case-form',
    'options' => ['class' => 'form-container'],
    'action' => '/case/case-reply',
]);
echo $form->field($model, 'reply_case')->textarea(
    ['placeholder' => 'Compose reply', 'class' => 'reply-text form-control requiredInput']
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
//BUTTONS
echo Html::beginTag('div', ['class' => 'incidence_buttons']);
if ($case->status === Status::STATUS_NEW) {
    echo Html::a(
        '<i class="fa fa-check" aria-hidden="true"> ACCEPT</i>',
        '#',
        ['class' => ' btn btn_reply edit-case',
            'data' => [
                'title' => 'Accept Case',
                'toggle' => 'modal',
                'target' => '#acceptCase',
                'url' => Url::toRoute("/users/accept-case?caseId={$case->id}"),
                'status' => Status::STATUS_PROGRESS,
                'msg' => Messages::getWarningMessage('Case', 'accept')
            ]
        ]
    );
    echo Html::a(
        '<i class="fa fa-times" aria-hidden="true"> DECLINE </i>',
        '#',
        ['class' => 'btn btn_back edit-case ',
            'data' => [
                'title' => 'Decline Case',
                'toggle' => 'modal',
                'target' => '#acceptCase',
                'url' => Url::toRoute("/users/decline-case-status?caseId={$case->id}"),
                'status' => Status::STATUS_DECLINED,
                'msg' => Messages::getWarningMessage('Case', 'decline')
            ]
        ]
    );
}
if ($case->status === Status::STATUS_PROGRESS) {
    echo Html::a(
        '<i class="fa fa-reply" aria-hidden="true"> REPLY</i>',
        '#',
        ['class' => ' btn btn_reply', 'id' => "reply-case"]
    );
    echo Html::a(
        '<i class="fa fa-times" aria-hidden="true"> BACK </i>',
        '/case/actor-list-view',
        ['class' => 'btn btn_back edit-case ']
    );
}
echo Html::endTag('div');
//modal
echo ConfirmCasesReminderModal::widget([
    'modalId' => 'acceptCase',
    'footerCancel' => Messages::NO,
    'footerSubmit' => Messages::YES,
    'model' => new CaseAssignment(),
]);
