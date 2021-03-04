<?php

namespace app\widgets\filters;

use kartik\daterange\DateRangePicker;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class FilterFieldWidget
 * @author Sholesi Kofoworola <kofoworola.sholesi@cottacush.com>
 * @author Malomo Damilare <damilaremalomo@gmail.com>
 * @package app\widgets
 */
class FilterFieldWidget extends Widget
{
    public $route;
    public $class = '';
    public $label;
    public $filters;
    public $dateRange;
    public $violation;
    public $source;
    public $country;

    public function run()
    {
        echo Html::beginForm($this->route, 'get', []);
        $this->renderLabel();
        echo Html::beginTag('div', ['class' => 'form-group mb-2 panel-body']);
        echo $this->renderFilters();
        echo $this->renderSubmitButton();
        echo Html::endTag('div');
        echo Html::endForm();
    }
    public function renderSubmitButton()
    {
        echo Html::submitButton(
            Html::tag('i', '', ['class' => 'fa fa-search']) .
            ' Filter',
            ['class' => 'btn filter-btn btn-success font-w600 btn-sm-150 mb-2']
        );
    }
    /**
     * @param $name
     * @param $value
     * @param $dropdownList
     * @param $prompt
     * @param string $extraClasses
     * @param null $id
     * @param array $data
     * @author Malomo Damilare <damilaremalomo@gmail.com>
     */
    public function renderDropDown($name, $value, $dropdownList, $prompt, $extraClasses = '', $id = null, $data = [])
    {

        echo Html::dropDownList($name, $value, $dropdownList, [
            'class' => 'form-control form-control-sm mr-sm-2 mb-2 ' .
                $extraClasses,
            'prompt' => $prompt,
            'data' => $data,
            'id' => $id
        ]);
    }
    /**
     * @param $name
     * @param $value
     * @param $placeholder
     * @param string $id
     * @throws \Exception
     * @author Malomo Damilare <damilaremalomo@gmail.com>
     */
    public function renderDateRange($name, $value, $placeholder, $id = '', $class = '')
    {

        echo DateRangePicker::widget([
            'name' => $name,
            'presetDropdown'=>true,
            'convertFormat'=>true,
            'hideInput' => true,
            'value' => $value,
            'class' => $class,
            'containerTemplate' => $this->getDateRangeTemplate($name, $class),
            'pluginOptions' => [
                'timePicker'=>true,
                'timePickerIncrement'=>15,
                'locale' => ['format' => 'Y-m-d'],
            ],
            'options' => [
                'placeholder' => $placeholder,
                'separator' => 'to',
                'class' => $class,
            ]
        ]);
    }
    public function renderFilters()
    {
        foreach ($this->filters as $filter) {
            if ($filter['type'] === 'dropdown') {
                $this->renderDropDown(
                    $filter['name'],
                    $filter['value'],
                    $filter['dropdownItems'],
                    $filter['prompt'],
                    ArrayHelper::getValue($filter, 'extraClasses'),
                    ArrayHelper::getValue($filter, 'id'),
                    ArrayHelper::getValue($filter, 'data')
                );
            } elseif ($filter['type'] === 'input') {
                $this->renderInput(
                    $filter['name'],
                    $filter['value'],
                    $filter['placeholder']
                );
            } elseif ($filter['type'] === 'date-range') {
                $this->renderDateRange(
                    $filter['name'],
                    $filter['value'],
                    $filter['placeholder'],
                    $filter['id'],
                    $filter['class']
                );
            }
        }
    }
    public function renderLabel()
    {
        if ($this->label) {
            echo Html::tag('label', $this->label, ['class' => 'control-label']);
        }
    }
    /**
     * @param $name
     * @param $value
     * @param $id
     * @param $placeholder
     * @return string
     * @author Malomo Damilare <damilaremalomo@gmail.com>
     */
    public function getDateRangeTemplate($name, $id)
    {
        return "<div id='date-range-container' class='kv-drp-container'>        
                    <div class='kv-drp-dropdown'>
                        <span class='left-ind mr-2'><i class='fa fa-calendar'></i></span>
                        <input type='text' readonly='' class='form-control form-control-sm form-control-sm-150 d-inline-block mr-sm-2 mb-2 range-value' value={value}>
                        <span class='right-ind'><b class='caret'></b></span>
                    </div>
                   {input}
                </div>";
    }
}