<?php
namespace app\widgets\modals;

use app\assets\UpdateModalStatusAsset;
use CottaCush\Yii2\Helpers\Html;
use yii\bootstrap4\Modal;

/**
 * Class ConfirmStatusUpdateModal
 * @package app\widgets
 * @author Malomo Damilare <damilaremalomo@gmail.com>
 */
class ConfirmStatusUpdateModal extends ActiveFormModalWidget
{
    public $modalId;
    public $populateFields;
    public $model;
    public $title;
    public $btnWidthHeight = '';
    public $classNames = '';
    public $footerSubmit = 'Proceed';
    public $footerCancel = 'Cancel';
    public $dnone = '';
    public $formOptions = ['class' => 'disable-submit-buttons'];
    public $message = null;
    public function beginModal()
    {

        Modal::begin([
            'title' => Html::tag('h4', $this->title, ['class' => 'modal-title']),
            'options' => [
                'id' => $this->modalId,
                'data-generic-modal' => 'true',
                'class' => 'modal fade status-modal' . $this->classNames
            ],
            'footer' =>
                Html::button(
                    $this->footerCancel,
                    ['
                        class' => 'btn btn-light no-edit-status-btn '. $this->dnone . $this->btnWidthHeight,
                        'data-dismiss' => 'modal'
                    ]
                ) . Html::submitButton(
                    $this->footerSubmit,
                    [
                        'class' => 'btn btn-light yes-edit-status-btn' . $this->btnWidthHeight,
                        'data-submit-modal-form' => ''
                    ]
                )
        ]);

    }
    public function renderContents()
    {
        echo Html::tag('p', $this->message, ['class' => '', 'data-msg' => is_null($this->message)]);
        echo $this->form->field($this->model, 'status')
            ->hiddenInput(['data-status' => true])
            ->label(false);
        echo Html::hiddenInput('id', null, ['data-id' => true]);
    }
}