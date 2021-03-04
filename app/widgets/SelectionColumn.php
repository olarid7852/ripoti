<?php


namespace app\widgets;

use app\libs\Utils;
use yii\grid\DataColumn;
use yii\helpers\Html;

  /**
     * Class SelectionColumn
     * @author Malomo Damilare <damilaremalomo@gmail.com>
     * @package app\widgets
     */
class SelectionColumn extends DataColumn
{
    public $label = '';
    public $format = 'raw';

    public function init()
    {
        parent::init();
        $this->header = $this->renderCheckbox('select-all', 'select-all');
        $this->value = function ($model) {
            return $this->renderCheckbox($model->id, 'bulk-select');
        };
    }

    /**
     * @param $name
     * @param $id
     * @return string
     * @author Malomo Damilare <damilaremalomo@gmail.com>
     */
    private function renderCheckbox($id, $name)
    {
        return Html::beginTag('div', ['class' => 'custom-control custom-checkbox text-center p-0']) .
            Html::checkbox($name, false, ['class' => 'custom-control-input', 'id' => $id, 'value' => $id]) .
            Html::label('', $id, ['class' => 'custom-control-label']) .
            Html::endTag('div');
    }
}
