<?php
namespace app\widgets\modals;

use app\assets\EditEmailViolationAsset;
use app\constants\StaticContent;
use app\assets\DependentSelectWidgetAsset;
use app\models\ViolationTypes;
use app\models\EmailReport;
use app\models\Country;
use app\widgets\DependentSelectWidget;
use CottaCush\Yii2\Helpers\Html;

class EditEmailViolationWidget extends ActiveFormModalWidget
{
    public $model;
    public $form;

    public function renderContents()
    {
        DependentSelectWidgetAsset::register($this->view);
        EditEmailViolationAsset::register($this->view);
        echo Html::beginTag('div', ['class' => 'edit-violation-input ']);
        echo $this->form->field($this->model, 'violation_type_id')
            ->dropDownList(ViolationTypes::getViolationTypes(), [
                'class' => 'form-control reportViolation report-input',
            ])->label('Edit Violation Type');
        echo Html::beginTag('div', ['class' => 'row']);
        echo Html::beginTag('div', ['class' => 'col-lg-12 col-xs-12']);
        echo DependentSelectWidget::widget([
            'form' => $this->form,
            'model' => $this->model,
            'items' => Country::getCountry(),
            'field' => 'country_id',
            'prompt' => 'Please select one',
            'classes' => 'form-group form-control report-input',
            'level' => 0,
            'dependent' => false,
            'label' => 'Country'
        ]);
        echo Html::endTag('div');
        echo Html::endTag('div');
        echo Html::endTag('div');
        echo Html::beginTag('div');
        echo $this->form->field($this->model, 'data_consent')->checkbox()
            ->label(
                '' .
                Html::tag('span', StaticContent::DATA_USE_CONSENT, ['class' => 'checkmark'])
            );
        echo Html::endTag('div');
    }
}