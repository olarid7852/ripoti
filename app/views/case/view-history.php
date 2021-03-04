<?php
use app\models\forms\CasesTypesForm;
use app\widgets\CustomGridViewWidget;
use app\widgets\ReportTitleWidget;
use yii\bootstrap4\Html;
echo Html::tag('h2', " CASE HISTORY ", ['class'=>' page-header']);
echo ReportTitleWidget::widget([
    'fileName' => 'Cases',
    'file' => 'View History',
    'urlName' => '/case/index',
]);

echo Html::beginTag('section', ['class' => 'table_report']);
echo CustomGridViewWidget::widget([
    'emptyStateIcon'=> 'shop',
    'emptyStateDescription' => 'No case history ',
    'emptyStateTitle' => 'Cases',
    'dataProvider' => $case,
    'columns' => [
        [
            'class' => 'yii\grid\SerialColumn',
            'header' => 'S/N',
        ],
        'actor:text:Actor',
        'role:text:Role',
        'action_status:text:Action',
        'created_at:datetime:Date created',
    ]
]);
echo Html::endTag('section');
