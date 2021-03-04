<?php
namespace app\widgets;

use app\assets\DependentSelectWidgetAsset;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use CottaCush\Yii2\Widgets\BaseWidget;

/**
 * Class DependentSelectWidget
 * @package app\widgets
 */
class DependentSelectWidget extends BaseWidget
{
    public $form;
    public $model;
    public $items;
    public $dependent = false;
    public $parent = null;
    public $classes = '';
    public $dataURL = null;
    public $field;
    public $level = 0; // highest level
    public $value;
    public $label;
    public $prompt;
    public $email;
    public $multiple = true;
    public $options = [];

    public function init()
    {
        DependentSelectWidgetAsset::register($this->view);
        if ($this->dependent) {
            $this->classes .= ' dependent-select';
            if (is_null($this->parent)) {
                throw new InvalidConfigException('A parent selector is required for dependent target selects');
            }
        }
    }
    public function run()
    {
        $attributes = [
            'prompt' => $this->prompt,
            'class' => $this->classes,
            'id' => $this->field,
            'data-url' => $this->dataURL,
            'data-parent' => $this->parent,
            'data-level' => $this->level,
            'value' => $this->value,
        ];
        echo $this->form->field($this->model, $this->field)
            ->dropDownList($this->items, ArrayHelper::merge($attributes, $this->options))
            ->label(($this->label) ?: false);
    }
}
