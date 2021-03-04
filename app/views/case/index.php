<?php

use app\constants\Messages;
use app\constants\Status;
use app\models\cases\CaseAssignment;
use app\models\forms\CasesTypesForm;
use app\widgets\BulkActionWidget;
use app\widgets\CustomGridViewWidget;
use app\widgets\filters\FilterFieldWidget;
use app\widgets\ListTitleWidget;
use app\widgets\modals\BulkActionModalWidget;
use app\widgets\modals\ConfirmCasesReminderModal;
use app\widgets\modals\ConfirmStatusUpdateModal;
use app\widgets\SelectionColumn;
use CottaCush\Yii2\Widgets\ActionButtons;
use yii\bootstrap4\Html;
use yii\helpers\Url;

//header
echo ListTitleWidget::widget([
    'title' => 'CASES',
    'btnClass' => ' invisible',
    'breadcrumb' => 'Cases'
]);
$isSuperAdmin = $model->isSuperAdmin();
//FILTER TABS
echo FilterFieldWidget::widget([
    'filters' => [
        [
            'type' => 'date-range',
            'name' => 'date_range',
            'value' => $dateRange,
            'placeholder' => 'PERIOD',
            'id' => 'date-range-input',
            'class' => 'filter-tab',
        ],
        [
            'type' => 'dropdown',
            'name' => 'status',
            'value' => $status,
            'prompt' => 'STATUS',
            'dropdownItems' => CasesTypesForm::$statusOptions,
            'id' => 'status_id',
        ]
    ]
]);
//Table
echo Html::beginTag('section', ['class' => 'table_report case_table']);
$route = Url::toRoute("update-bulk-status");
echo BulkActionWidget::widget([
    'actions' => [
        [
            'url' => $route,
            'label' => 'Resolve',
            'title' => 'Resolve all',
            'value' => Status::STATUS_RESOLVED,
            'msg' => Messages::getWarningMessage('Case(s)', 'resolve'),
        ],
        [
            'label' => 'Withdraw',
            'title' => 'Withdraw all',
            'url' => $route,
            'value' => Status::STATUS_WITHDRAW,
            'msg' => Messages::getWarningMessage('Case(s)', 'withdraw')
        ]
    ]
]);
echo CustomGridViewWidget::widget([
    'emptyStateIcon' => 'shop',
    'emptyStateDescription' => 'No cases yet',
    'emptyStateTitle' => 'Cases',
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'class' => 'yii\grid\SerialColumn',
            'header' => 'S/N',
        ],
        'case_id:text:Case No',
        [
            'class' => SelectionColumn::class,
            'visible' => true,
        ],
        'reportDetails.violation.names:text:Violation Type',
        'reportDetails.reporterName:text:Reporter',
        'created_at:date:Date Created',
        [
            'header' => 'Status',
            'visible' => true,
            'value' => 'statusHTML',
            'format' => 'HTML'
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Action',
            'template' => '{actions}',
            'buttons' => [
                'actions' => function ($url, CasesTypesForm $case, $key) {
                    $isPending = $case->status === Status::STATUS_PENDING;
                    $isUnassigned = $case->status === Status::STATUS_UNASSIGNED;
                    $isResolved = $case->status === Status::STATUS_RESOLVED;
                    $actions = [
                        [
                            'label' => '<i class="fa fa-eye" aria-hidden="true"></i> View ',
                            'url' => Url::toRoute("admin-case-view?caseId={$case->id}"),
                            'visible' => !$isUnassigned,
                            'options' => ['class' => 'edit-status'],
                            'id' => 'view',
                        ],
                        [
                            'label' => '<i class="fa fa-refresh" aria-hidden="true"></i> Assign Case',
                            'url' => Url::toRoute("new-case?caseId={$case->id}"),
                            'visible' => true,
                            'class' => 'edit-status',
                        ],
                        [
                            'label' => '<i class="fa fa-check-circle" aria-hidden="true"></i> Mark as Resolve',
                            'url' => '#',
                            'visible' => !$isUnassigned && ( !$isPending && !$isResolved ),
                            'id' => 'resolve',
                            'options' => [
                                'class' => 'edit-status',
                                'data' => [
                                    'title' => 'Mark as Resolved',
                                    'toggle' => 'modal',
                                    'target' => '#editCase',
                                    'url' => Url::toRoute("resolve-case?caseId={$case->id}"),
                                    'status' => Status::STATUS_RESOLVED,
                                    'msg' => Messages::getWarningMessage('Case', 'resolve')
                                ]
                            ]
                        ],
                        [
                            'label' => '<i class="fa fa-history" aria-hidden="true"></i> View History',
                            'url' => Url::toRoute("view-history?caseId={$case->id}"),
                            'class' => 'edit-action',
                            'visible' => !$isUnassigned,
                        ],
                        [
                            'label' => '<i class="fa fa-bell-o" aria-hidden="true"></i> Send Reminder',
                            'url' => '#',
                            'visible' => $isPending,
                            'options' => [
                                'class' => 'edit-violation',
                                'data' => [
                                    'title' => 'Send Reminder to Assignees',
                                    'toggle' => 'modal',
                                    'target' => '#sendReminder',
                                    'url' => Url::toRoute("case-reminder?caseId={$case->id}"),
                                    'msg' => Messages::getWarningMessage('case', 'send a reminder for')

                                ]
                            ]
                        ],
                    ];
                    return ActionButtons::widget(['actions' => $actions]);
                }
            ]
        ]
    ]
]);

echo Html::endTag('section');
//UPDATE CASE STATUS MODAL
echo ConfirmStatusUpdateModal::widget([
    'modalId' => 'editCase',
    'footerCancel' => Messages::NO,
    'footerSubmit' => Messages::YES,
    'model' => new CasesTypesForm(),
    'btnWidthHeight' => ' modal_btn',
]);
//REMINDER CASE MODAL
echo ConfirmCasesReminderModal::widget([
    'modalId' => 'sendReminder',
    'footerCancel' => Messages::NO,
    'footerSubmit' => Messages::YES,
    'model' => new CaseAssignment(),
    'btnWidthHeight' => ' modal_btn',
    'formId' => 'reminder-modal-form'
]);
//BULK ACTION MODAL
echo BulkActionModalWidget::widget([
    'modalId' => 'bulk-action-modal',
    'footerCancel' => Messages::NO,
    'footerSubmit' => Messages::YES,
    'btnWidthHeights' => ' btn cancel',
    'btnWidthHeight' => ' btn submit',
    'formId' => 'bulk-action-form',
    'id' => 'bulk-action-modal',
]);
