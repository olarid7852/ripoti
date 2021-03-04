<?php

use app\constants\Messages;
use app\constants\Status;
use app\models\Country;
use app\models\forms\ReportForm;
use app\models\forms\ReportTypesForm;
use app\models\twitter\TwitterReport;
use app\models\EmailReport;
use app\models\ViolationTypes;
use app\widgets\CustomGridViewWidget;
use app\widgets\filters\FilterFieldWidget;
use app\widgets\ListTitleWidget;
use app\constants\Source;
use app\widgets\modals\ConfirmStatusUpdateModal;
use app\widgets\modals\EditReportViolationTypeModal;
use app\widgets\modals\EditEmailViolationWidget;
use CottaCush\Yii2\Widgets\ActionButtons;
use yii\helpers\Html;
use app\assets\MaterialDesignIconsAsset;
use yii\helpers\Url;

MaterialDesignIconsAsset::register($this);
//HEADER
echo ListTitleWidget::widget([
    'title' => 'REPORTS',
    'btnClass' => ' invisible',
    'breadcrumb' => 'reports '
]);
//FILTER TABS
echo FilterFieldWidget::widget([
    'filters' => [
        [
            'type' => 'dropdown',
            'name' => 'violation',
            'value' => $violation,
            'prompt' => 'VIOLATIONS',
            'dropdownItems' => ViolationTypes::getViolationTypes(),
            'id' => 'violation_id',
        ],
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
            'name' => 'source',
            'value' => $source,
            'prompt' => 'SOURCE',
            'dropdownItems' => ReportTypesForm::$sourceType,
            'id' => 'source_id',

        ],
        [
            'type' => 'dropdown',
            'name' => 'country_id',
            'value' => $country,
            'prompt' => 'COUNTRY',
            'dropdownItems' => Country::getCountry(),
            'id' => 'country_id',
        ],
        [
            'type' => 'dropdown',
            'name' => 'status',
            'value' => $status,
            'prompt' => 'STATUS',
            'dropdownItems' => ReportTypesForm::$status,
            'id' => 'status_id',
        ]
    ]
]);

//REPORT LIST TABLE
echo Html::beginTag('section', ['class' => 'table_report']);

echo CustomGridViewWidget::widget([
        'emptyStateIcon' => 'assignment',
        'emptyStateDescription' => 'No Report Available',
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => 'S/N'
            ],
            'violation.names:text:Violation Type',
            'reporterName:text:Reporter',
            'created_at:date: Reporting Date',
            [
                'header' => 'Data Consent',
                'format' => 'html',
                'value' => function ($model, $key, $index, $column) {
                    return $model->data_consent == 0 ? 'NO' : 'YES';
                }
            ],
            'statusHTML:html:Status',
            [
                'header' => 'Source',
                'value' => 'reportSource',
                'format' => 'html'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Action',
                'template' => '{actions}',
                'buttons' => [
                    'actions' => function ($url, ReportTypesForm $report, $key) {
                        $isVerified = $report->status === Status::STATUS_VERIFIED;
                        $isCase = $report->status === Status::STATUS_CASE;
                        $isTwitter= $report->source == Source::SOURCE_TWITTER;
                        $isEmail= $report->source == Source::SOURCE_EMAIL;
                        $actions = [
                            [
                                'label' => '<i class="fa fa-eye" aria-hidden="true"></i> View',
                                'url' => Url::toRoute("view-report?reportId={$report->id}"),
                                'options' => ['class' => 'edit-status'],
                                'id' => 'view',
                            ],
                            [
                                'label' => '<i class="fa fa-check" aria-hidden="true"></i> Verify',
                                'url' => '#',
                                'id' => 'verify',
                                'visible' => !$isVerified && !$isCase,
                                'options' => [
                                    'class' => 'edit-status',
                                    'data' => [
                                        'title' => 'Verify Report',
                                        'toggle' => 'modal',
                                        'target' => '#editStatus',
                                        'url' => Url::toRoute("update-report-status?reportId={$report->id}"),
                                        'status' => Status::STATUS_VERIFIED,
                                        'msg' => Messages::getWarningMessage('Report', 'verify')
                                    ]
                                ]
                            ],
                            [
                                'label' => '<i class="fa fa-pencil" aria-hidden="true"></i> Edit',
                                'url' => '#',
                                'visible' => $isTwitter,
                                'id' => '',
                                'options' => [
                                    'class' => 'edit-violation',
                                    'data' => [
                                        'title' => 'Edit Violation Type',
                                        'toggle' => 'modal',
                                        'target' => '#editModal',
                                        'url' => Url::toRoute("edit-violation?reportId={$report->twitter_report_id}"),
                                    ]
                                ]

                            ],
                            [
                                'label' => '<i class="fa fa-pencil" aria-hidden="true"></i> Edit',
                                'url' => '#',
                                'visible' => $isEmail,
                                'id' => '',
                                'options' => [
                                    'class' => 'edit-violation',
                                    'data' => [
                                        'title' => 'Edit Violation Type',
                                        'toggle' => 'modal',
                                        'target' => '#editViolation',
                                        'url' => Url::toRoute("email-violation?reportId={$report->email_report_id}"),
                                    ]
                                ]
                            ],
                            [
                                'label' => '<i class="fa fa-camera" aria-hidden="true"></i> Create Case',
                                'url' => '#',
                                'id' => 'unverify',
                                'visible' => $isVerified & !$isCase,
                                'options' => [
                                    'class' => 'edit-status',
                                    'data' => [
                                        'title' => 'Create new case',
                                        'toggle' => 'modal',
                                        'target' => '#editStatus',
                                        'url' => Url::toRoute("create-case?reportId={$report->id}"),
                                        'status' => Status::STATUS_UNVERIFIED,
                                        'msg' => Messages::getWarningMessage('Report', 'make a case for ')
                                    ]
                                ]
                            ],
                        ];
                        return ActionButtons::widget(['actions' => $actions]);
                    }
                ]
            ]
        ]
    ]
);
echo Html::endTag('section');

//TOGGLE VIOLATION STATUS
echo ConfirmStatusUpdateModal::widget([
    'modalId' => 'editStatus',
    'footerCancel' => Messages::NO,
    'footerSubmit' => Messages::YES,
    'model' => new ReportTypesForm(),
    'btnWidthHeight' => ' widthHeight',
]);
//EDIT VIOLATION TYPE
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
//end
