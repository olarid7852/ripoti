<?php

namespace app\widgets\modals;

use app\assets\EditViolationTypeAsset;
use CottaCush\Yii2\Helpers\Html;

class EditViolationTypeModal extends ActiveFormModalWidget
{
    public function renderContents()
    {
        EditViolationTypeAsset::register($this->view);
        echo Html::beginTag('div', ['class' => 'edit-violations-form']);
        echo Html::beginTag('div', ['class' => 'edit-violation-input']);
        echo $this->form->field($this->model, 'names')
            ->textInput(['class' => 'form-control violationField'])->label('Edit Violation Type');
        echo Html::endTag('div');
        echo Html::beginTag('div', ['class' => 'edit-violation-select']);
        $status = ['active' => 'ACTIVE', 'inactive' => 'INACTIVE'];
        echo $this->form->field($this->model, 'status')
            ->dropDownList(
                $status
            )
            ->label('Status');
        echo Html::endTag('div');
        echo Html::endTag('div');
    }
}
