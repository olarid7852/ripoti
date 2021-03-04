<?php

use app\assets\DashboardAsset;
use app\models\Admin;
use app\models\Country;
use app\models\forms\CasesTypesForm;
use app\models\forms\ReportTypesForm;
use app\widgets\CustomGridViewWidget;
use app\widgets\filters\FilterFieldWidget;
use app\widgets\ListTitleWidget;
use yii\bootstrap4\Html;
use yii\helpers\ArrayHelper;

/** @var Admin $users */
/** @var Country $country */
/** @var CasesTypesForm $cases */
/** @var CasesTypesForm $caseResult */
/** @var CasesTypesForm $pendingCases */
/** @var CasesTypesForm $withdrawnCases */
/** @var CasesTypesForm $resolvedCases */
/** @var ReportTypesForm $dateRange */
/** @var ReportTypesForm $reports */
/** @var ReportTypesForm $reportData */
/** @var ReportTypesForm $reportResult */
/** @var ReportTypesForm $unVerifiedReports */
/** @var ReportTypesForm $verifiedReports */
DashboardAsset::register($this);
$user = \Yii::$app->user->identity;
//BREADCRUMBS
echo ListTitleWidget::widget([
    'title' => 'DASHBOARD',
    'btnClass' => ' invisible',
    'breadcrumb' => 'Dashboard'
]);

if ($user->role_key === Admin::SUPER_ADMIN) {
    //SORT FILTERS
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
                'name' => 'country_id',
                'value' => $country,
                'prompt' => 'COUNTRY',
                'dropdownItems' => Country::getCountry(),
                'id' => 'country_id',
            ]
        ]
    ]);
//CENTER CONTENT
    echo Html::beginTag('div', ['class' => 'row']);
    echo Html::beginTag('div', ['class' => 'col-md-4']);
    echo Html::beginTag('div', ['class' => 'p-4 item-body']);
    echo Html::tag('span', 'assignment', ['class' => 'material-icons mt-4 mr-4 d-icon']);
    echo Html::tag('h2',
        Html::tag('p', 'Total Number of Reports', ['class' => 'label']) . $reports, ['class' => 'figure']);
    echo Html::endTag('div');
    echo Html::endTag('div');

    echo Html::beginTag('div', ['class' => 'col-md-4']);
    echo Html::beginTag('div', ['class' => 'p-4 col item-body']);
    echo Html::tag('span', 'shop', ['class' => 'material-icons mt-4 mr-4 d-icon']);
    echo Html::tag('h2',
        Html::tag('p', 'Total Number of Cases', ['class' => 'label']) . $cases, ['class' => 'figure']);
    echo Html::endTag('div');
    echo Html::endTag('div');

    echo Html::beginTag('div', ['class' => 'col-md-4']);
    echo Html::beginTag('div', ['class' => 'p-4 col item-body']);
    echo Html::tag('span', 'assignment_ind', ['class' => 'material-icons mt-4 mr-4 d-icon']);
    echo Html::tag('h2',
        Html::tag('p', 'Total Number of Active Users', ['class' => 'label']) . $users, ['class' => 'figure']);
    echo Html::endTag('div');
    echo Html::endTag('div');
    echo Html::endTag('div');

    echo Html::beginTag('div', ['class' => 'row']);
    echo Html::beginTag('div', ['class' => 'col-md-6 ']);
    echo Html::beginTag('div', ['class' => 'p-4  charts ']);
    echo Html::tag('p', 'Reports', ['class' => ' mt-2 mb-3 report_cases']);
    echo Html::beginTag('div', ['class' => 'chart_container']);
    echo Html::beginTag('canvas', ['class' => 'chart', 'id' => 'reportChart']);
    echo Html::endTag('canvas');
    echo Html::endTag('div');
    echo Html::endTag('div');
    echo Html::endTag('div');

    echo Html::beginTag('div', ['class' => 'col-md-6']);
    echo Html::beginTag('div', ['class' => 'p-4 charts']);
    echo Html::tag('p', 'Cases', ['class' => 'mt-2 mb-3 report_cases']);
    echo Html::beginTag('div', ['class' => 'chart_container']);
    echo Html::beginTag('canvas', ['class' => 'chart', 'id' => 'caseChart']);
    echo Html::endTag('canvas');
    echo Html::endTag('div');
    echo Html::endTag('div');
    echo Html::endTag('div');
    echo Html::endTag('div');

    echo Html::beginTag('div', ['class' => 'row']);
    echo Html::beginTag('div', ['class' => 'col-md-5']);
    echo Html::beginTag('div', ['class' => 'p-4 charts ']);
    echo Html::tag('p', 'Most reported violation types', ['class' => 'mt-2 mb-3 report_table']);
    echo CustomGridViewWidget::widget([
        'emptyStateIcon' => 'assignment',
        'emptyStateDescription' => 'No Report Available',
        'dataProvider' => $reportData,
        'columns' => [
            [
                'header' => 'Violation Type',
                'visible' => true,
                'value' => 'names',
                'format' => 'text'
            ],
            [
                'header' => 'No',
                'visible' => true,
                'value' => 'reportViolations',
                'format' => 'HTML'
            ]]
    ]);
    echo Html::endTag('div');
    echo Html::endTag('div');
    echo Html::beginTag('div', ['class' => 'col-md-7']);
    echo Html::beginTag('div', ['class' => 'px-4 pt-3 charts scroll']);
    echo Html::tag('p', 'Reports and Cases', ['class' => 'mt-2  report_table']);
    echo Html::beginTag('div', ['class' => 'chart-containers']);
    echo Html::beginTag('canvas', ['class' => 'chart', 'id' => 'reportCaseGraph']);
    echo Html::endTag('canvas');
    echo Html::endTag('div');
    echo Html::endTag('div');
    echo Html::endTag('div');
    echo Html::endTag('div');
}
if ($user->role_key !== Admin::SUPER_ADMIN) {
    echo Html::beginTag('div', ['class' => 'text-center mt-5']);
    echo Html::tag('span', 'Hello', ['class' => 'welcome dashboard-logo']);
    echo Html::tag(
        'p',
        'Welcome to your Ripoti Dashboard',
        ['class' => 'note']
    );
    echo Html::endTag('div');
}
?>
<script type="text/javascript">
    let januaryReports = <?= json_encode(ArrayHelper::getValue($reportResult, '1')); ?>;
    let februaryReports = <?= json_encode(ArrayHelper::getValue($reportResult, '2')); ?>;
    let marchReports = <?= json_encode(ArrayHelper::getValue($reportResult, '3')); ?>;
    let aprilReports = <?= json_encode(ArrayHelper::getValue($reportResult, '4')); ?>;
    let mayReports = <?= json_encode(ArrayHelper::getValue($reportResult, '5')); ?>;
    let juneReports = <?= json_encode(ArrayHelper::getValue($reportResult, '6')); ?>;
    let julyReports = <?= json_encode(ArrayHelper::getValue($reportResult, '7')); ?>;
    let augustReports = <?= json_encode(ArrayHelper::getValue($reportResult, '8')); ?>;
    let septemberReports = <?= json_encode(ArrayHelper::getValue($reportResult, '9')); ?>;
    let octoberReports = <?= json_encode(ArrayHelper::getValue($reportResult, '10')); ?>;
    let novemberReports = <?= json_encode(ArrayHelper::getValue($reportResult, '11')); ?>;
    let decemberReports = <?= json_encode(ArrayHelper::getValue($reportResult, '12')); ?>;

    let januaryCases = <?= json_encode(ArrayHelper::getValue($caseResult, '1')); ?>;
    let februaryCases = <?= json_encode(ArrayHelper::getValue($caseResult, '2')); ?>;
    let marchCases = <?= json_encode(ArrayHelper::getValue($caseResult, '3')); ?>;
    let aprilCases = <?= json_encode(ArrayHelper::getValue($caseResult, '4')); ?>;
    let mayCases = <?= json_encode(ArrayHelper::getValue($caseResult, '5')); ?>;
    let juneCases = <?= json_encode(ArrayHelper::getValue($caseResult, '6')); ?>;
    let julyCases = <?= json_encode(ArrayHelper::getValue($caseResult, '7')); ?>;
    let augustCases = <?= json_encode(ArrayHelper::getValue($caseResult, '8')); ?>;
    let septemberCases = <?= json_encode(ArrayHelper::getValue($caseResult, '9')); ?>;
    let octoberCases = <?= json_encode(ArrayHelper::getValue($caseResult, '10')); ?>;
    let novemberCases = <?= json_encode(ArrayHelper::getValue($caseResult, '11')); ?>;
    let decemberCases = <?= json_encode(ArrayHelper::getValue($caseResult, '12')); ?>;
    let unverifiedReports = <?= json_encode($unVerifiedReports); ?>;
    let verifiedReports = <?= json_encode($verifiedReports); ?>;
    let pendingCases = <?= json_encode($pendingCases); ?>;
    let withdrawnCases = <?= json_encode($withdrawnCases); ?>;
    let resolvedCases = <?= json_encode($resolvedCases); ?>;
</script>
