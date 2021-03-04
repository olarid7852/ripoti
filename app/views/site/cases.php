<?php

use app\assets\SiteCasesAsset;
use app\models\forms\ReportTypesForm;
use yii\bootstrap4\Html;
use yii\helpers\ArrayHelper;

/** @var ReportTypesForm $cases */
/** @var ReportTypesForm $reports  */
SiteCasesAsset::register($this);
echo Html::beginTag('div', ['class' => 'd-flex justify-content-center']);
echo Html::tag('p', 'CASES', ['class' => 'mb-4 head_reported']);
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'row']);
echo Html::beginTag('div', ['class' => 'col-lg-5 ']);
echo Html::beginTag('div', ['class' => 'p-4 item-body ']);
echo Html::tag('span', 'assignment', ['class' => 'material-icons mt-4 mr-4 d-icon']);
echo Html::tag('h2',
    Html::tag('p', 'Total Number of Reports', ['class' => 'label']) . ($reports), ['class' => 'figure']);
echo Html::endTag('div');
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'col-lg-5 offset-lg-2 case_verified']);
echo Html::beginTag('div', ['class' => 'p-4 item-body']);
echo Html::tag('span', 'shop', ['class' => 'material-icons mt-4 mr-4 d-icon']);
echo Html::tag('h2',
    Html::tag('p', 'Verified Cases', ['class' => 'label']) . ($cases), ['class' => 'figure']);
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'row']);
echo Html::beginTag('div', ['class' => 'col-md-6 ']);
echo Html::beginTag('div', ['class' => 'p-4 chart scroll']);
echo Html::beginTag('div', ['class' => 'cases-chart']);
echo Html::beginTag('canvas', ['class' => 'case-chart', 'id' => 'countryChart']);
echo Html::endTag('canvas');
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'col-md-6']);
echo Html::beginTag('div', ['class' => 'p-4 chart scroll']);
echo Html::beginTag('div', ['class' => 'cases-chart']);
echo Html::beginTag('canvas', ['class' => 'case-chart', 'id' => 'violationsChart']);

echo Html::endTag('canvas');
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'd-flex justify-content-center']);
echo Html::a('Download PDF','#', ['class' => 'download_button invisible', 'id' => 'download']);
echo Html::endTag('div');

?>
<script type="text/javascript">
    let violationResultKeys = <?= json_encode(array_keys($violationResult)); ?>;
    let violationResultValues = <?= json_encode(array_values($violationResult)); ?>;
    let countryResultKeys = <?= json_encode(array_keys($countryResult)); ?>;
    let countryResultValues = <?= json_encode(array_values($countryResult)); ?>;
</script>
