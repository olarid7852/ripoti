<?php

namespace app\widgets\modals;

use CottaCush\Yii2\Widgets\BaseModalWidget;
use yii\bootstrap4\Modal;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/**
 * Class ActiveFormModalWidget
 * @package app\widgets
 * @author Malomo Damilare <damilaremalomo@gmail.com>
 */
class ActiveFormModalWidget extends BaseModalWidget
{
    public $classNames = '';
    public $size;
    public $showFooterBtn = true;
    public $formId = 'form';
    public $formClass = '';
    public $enctype = 'application/x-www-form-urlencoded';
    public $genericModal = false;
    public $modalId;
    public $populateFields;
    public $model;
    public $title;
    public $btnWidthHeights = '';
    public $btnWidthHeight = '';
    public $footerSubmit = '';
    public $footerCancel = '';
    public $message = null;

    public function beginModal()
    {
        Modal::begin([
            'title' => \CottaCush\Yii2\Helpers\Html::tag('h4', $this->title, ['class' => 'modal-title']),
            'options' => $this->renderModalOptions(),
            'size' => $this->size,
            'footer' =>
                Html::button(
                    $this->footerCancel,
                    ['class' => 'btn btn-light no-edit-status-btn ' . $this->btnWidthHeights, 'data-dismiss' => 'modal']
                ) .
                Html::submitButton(
                    $this->footerSubmit,
                    [
                        'class' => 'btn update-btn yes-edit-status-btn ' . $this->btnWidthHeight,
                        'data-submit-modal-form' => '' , 'id' => 'invite_btn'
                    ]
                )

        ]);

    }
    public function beginForm()
    {
        $this->form = ActiveForm::begin([
            'action' => $this->route,
            'method' => $this->formMethod,
            'options' => [
                'id' => $this->formId,
                'class' => $this->formClass,
                'enctype' => $this->enctype
            ],
            'fieldConfig' => [
                'template' => "<div>{label}</div><div class='font-size-nm'>{input}{error}</div>",
                'labelOptions' => ['class' => 'control-label'],
            ]
        ]);
    }
    public function endForm()
    {
        ActiveForm::end();
    }
    public function endModal()
    {
        if ($this->populateFields) {
            $this->view->registerJs(
                "App.Components.Modal.populateModal('#" . $this->id . "','" . $this->model->formName() . "');"
            );
        }
        Modal::end();
    }
    /**
     * @param \yii\widgets\ActiveForm $form
     * @param $model
     * @param $attribute
     * @return mixed
     * @author Olawale Lawal <wale@cottacush.com>
     */
    protected function renderHiddenInput($form, $model, $attribute)
    {
        return $form->field($model, $attribute, ['options' => ['hidden' => true]])->hiddenInput()->label(false);
    }
    protected function renderModalOptions()
    {
        $options = [
            'id' => $this->modalId,
            'class' => 'modal fade active-form-modal status-modal ' . $this->classNames,
        ];
        if ($this->genericModal) {
            $options = array_merge($options, ['data-generic-modal' => 'true']);
        }
        return $options;
    }
}