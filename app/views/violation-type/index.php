<?php

use app\constants\Messages;
use app\models\Status;
use app\models\ViolationTypes;
use app\widgets\CustomGridViewWidget;
use app\widgets\modals\ConfirmStatusUpdateModal;
use app\widgets\modals\EditViolationTypeModal;
use CottaCush\Yii2\Widgets\ActionButtons;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\bootstrap4\Modal;
use app\widgets\ListTitleWidget;
use yii\helpers\Url;

//HEADER
echo ListTitleWidget::widget([
    'title' => 'VIOLATION' . ' TYPES',
    'btn_name' => 'Violation' . ' Type',
    'breadcrumb' => 'Violations'
]);

//VIOLATION TYPES TABLE
echo Html::beginTag('section', ['class' => 'table_report']);
echo CustomGridViewWidget::widget(
    [
        'emptyStateIcon' => 'new_releases',
        'emptyStateDescription' => 'No violation type has been added',
        'emptyStateTitle' => 'Violations',
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => 'S/N',
            ],
            'names:text:Violation Type',
            'statusHTML:html:Status',
            'created_at:datetime:Date Created',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Action',
                'template' => '{actions}',
                'buttons' => [
                    'actions' => function ($url, ViolationTypes $violation, $key) {
                        $isActive = $violation->status === Status::STATUS_ACTIVE;
                        $actions = [
                            [
                                'label' => '<i class="fa fa-pencil" aria-hidden="true"></i> Edit',
                                'url' => '#',
                                'options' => [
                                    'class' => 'edit-violation',
                                    'data' => [
                                        'title' => 'Edit Violation Type',
                                        'toggle' => 'modal',
                                        'target' => '#editModal',
                                        'url' => Url::toRoute("edit-violation?violationId={$violation->id}"),
                                        'violation' => $violation->names,
                                        'status' => $violation->status,
                                    ]
                                ]
                            ],
                            [
                                'label' => '<i class="fa fa-times-circle" aria-hidden="true"></i> Deactivate',
                                'url' => '#',
                                'visible' => $isActive,
                                'options' => [
                                    'class' => 'edit-status',
                                    'data' => [
                                        'title' => 'Deactivate Violation',
                                        'toggle' => 'modal',
                                        'target' => '#editStatus',
                                        'url' => Url::toRoute(
                                            "update-violation-status?violationId={$violation->id}"
                                        ),
                                        'status' => Status::STATUS_INACTIVE,
                                        'msg' => Messages::getWarningMessage('Violation', 'deactivate')
                                    ]
                                ]
                            ],
                            [
                                'label' => '<i class="fa fa-check-circle" aria-hidden="true"></i> Activate',
                                'url' => '#',
                                'visible' => !$isActive,
                                'options' => [
                                    'class' => 'edit-status',
                                    'data' => [
                                        'title' => 'Activate Violation',
                                        'toggle' => 'modal',
                                        'target' => '#editStatus',
                                        'url' => Url::toRoute("update-violation-status?violationId={$violation->id}"),
                                        'status' => Status::STATUS_ACTIVE,
                                        'msg' => Messages::getWarningMessage('Violation', 'activate')
                                    ]
                                ]
                            ],
                        ];
                        return ActionButtons::widget(['actions' => $actions]);
                    }
                ]
            ]
        ]
    ]
);
echo Html::endTag('div');

//TOGGLE VIOLATION STATUS
echo ConfirmStatusUpdateModal::widget([
    'modalId' => 'editStatus',
    'footerCancel' => Messages::NO,
    'footerSubmit' => Messages::YES,
    'model' => new ViolationTypes(),
]);

//EDIT VIOLATIONS MODAL
echo EditViolationTypeModal::widget([
    'modalId' => 'editModal',
    'title' => 'Edit Violation Type',
    'classNames' => 'edit-violation-modal',
    'footerCancel' => Messages::ACTION_CANCEL,
    'footerSubmit' => Messages::ACTION_UPDATE,
    'model' => new ViolationTypes(),
    'formId' => 'edit-modal-form',
    'populateFields' => true,
  
]);

//ADD VIOLATIONS MODAL
Modal::begin([
    'title' => '<h4 class="modal-title"> Add New Violation Type</h4>',
    'id' => 'modal',
    'size' => 'md-modal'
]);
$form = ActiveForm::begin([
    'id' => 'violation-types-form',
    'options' => ['class' => 'add-form'],
    'action' => '/violation-type/create-violation-types'
]);
echo Html::beginTag('div', ['class' => 'admin_role field-display']);
echo $form->field($model, 'names')
    ->textInput(['class' => 'text-field', 'placeholder' => 'Enter new Violation type']);
echo Html::beginTag('div');
echo Html::submitButton('ADD', ['class' => 'add-type']);
echo Html::endTag('div');
echo Html::endTag('div');
echo Html::tag(
    'p',
    'Enter multiple Violation types separated by a comma',
    ['class' => 'modal-text']
);
$form::end();
Modal::end();
//END