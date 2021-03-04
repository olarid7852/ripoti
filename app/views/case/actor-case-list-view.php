<?php

use app\models\cases\CaseAssignment;
use app\widgets\CustomGridViewWidget;
use app\widgets\ListTitleWidget;
use CottaCush\Yii2\Widgets\ActionButtons;
use yii\bootstrap4\Html;
use yii\helpers\Url;

//header
echo ListTitleWidget::widget([
    'title' => 'CASES',
    'btnClass' => ' invisible',
    'breadcrumb' => 'Cases'
]);

//Table
echo Html::beginTag('section', ['class' => 'table_report case_table']);
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
        'caseDetails.case_id:text:Case No',
        'caseDetails.reportDetails.violation.names:text:Violation Type',
        'caseDetails.reportDetails.reporterName:text:Reporter',
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
                'actions' => function ($url, CaseAssignment $case, $key) {
                    $actions = [
                        [
                            'label' => '<i class="fa fa-eye" aria-hidden="true"></i> View Case',
                            'url' => Url::toRoute("view-case?caseId={$case->caseDetails->id}"),
                            'visible' => true,
                        ]
                    ];
                    return ActionButtons::widget(['actions' => $actions]);
                }
            ]
        ]
    ]
]);
echo Html::endTag('section');
