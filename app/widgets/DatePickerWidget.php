<?php

namespace app\widgets;

use kartik\date\DatePicker;

/**
 * Class DatePickerWidget
 * @package app\widgets
 * @author Malomo Damilare <damilaremalomo@gmail.com>
 */
class DatePickerWidget extends BaseWidget
{
    public $overrideOptions = [];
    public $model;
    public $form;
    public $attribute;
    public $type;
    public $start_attribute;
    public $end_attribute;
    public $format;

    public function run()
    {
        $options = $this->getOptions();
        echo DatePicker::widget($options);
    }
    /**
     * @return array
     * @author Malomo Damilare <damilaremalomo@gmail.com>
     */
    private function getOptions()
    {
        $defaultOptions = [
            'model' => $this->model,
            'attribute' => $this->attribute,
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'removeButton' => false,
            'pluginOptions' => [
                'orientation' => 'top left',
                'todayHighlight' => true,
                'endDate'=> 'today',
                'maxDate' => 'today',
                'todayBtn' => false,
                'autoclose'=>true,
                'format' => 'yyyy-M-dd'
            ]
        ];
        return $this->overrideOptions ?
            array_replace_recursive($defaultOptions, $this->overrideOptions) :
            $defaultOptions;
    }
}