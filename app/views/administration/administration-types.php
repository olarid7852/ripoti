<?php

use app\constants\Messages;
use app\constants\Status;
use app\models\Role;
use app\widgets\ListTitleWidget;
use app\widgets\modals\AdministrationRoleModalWidget;
use app\widgets\CustomGridViewWidget;
use app\widgets\modals\ConfirmStatusUpdateModal;
use CottaCush\Yii2\Widgets\ActionButtons;
use yii\helpers\Html;
use yii\helpers\Url;

echo ListTitleWidget::widget([
    'title' => 'ADMINISTRATION',
    'btn_name' => 'role',
    'breadcrumb' => 'administration'
]);
//Table
echo Html::beginTag('section', ['class' => 'table_report']);
echo CustomGridViewWidget::widget([
        'emptyStateIcon' => 'group',
        'emptyStateDescription' => 'No roles have currently been assigned',
        'emptyStateTitle' => 'Administration',
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => 'S/N',
            ],
            'label:text:Role',
            'statusHtml:html:Status',
            'created_at:date:Date Created',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Action',
                'template' => '{actions}',
                'buttons' => [
                    'actions' => function ($url, Role $role, $key) {
                        $isActive = $role->status === Status::STATUS_ACTIVE;
                        $actions = [
                            [
                                'label' => '<i class="fa fa-pencil" aria-hidden="true"></i> Edit',
                                'url' => '#',
                                'options' => [
                                    'class' => 'edit_role',
                                    'data' => [
                                        'title' => 'Edit Role',
                                        'toggle' => 'modal',
                                        'target' => '#new_role_modal',
                                        'url' => Url::toRoute("edit-role-permission?roleId={$role->id}"),
                                        'status' => $role->status,
                                        'permissions[]' => $role->getPermissions(),
                                        'label' => $role->label,
                                        'msg' => Messages::getWarningMessage('Role', 'Edit')
                                    ],
                                ],
                            ],
                            [
                                'label' => '<i class="fa fa-times-circle" aria-hidden="true"></i> Deactivate',
                                'url' => '#',
                                'visible' => $isActive,
                                'options' => [
                                    'class' => 'edit-status',
                                    'data' => [
                                        'title' => 'Deactivate Role',
                                        'toggle' => 'modal',
                                        'target' => '#editStatus',
                                        'url' => Url::toRoute("update-role-status?roleId={$role->id}"),
                                        'status' => Status::STATUS_INACTIVE,
                                        'msg' => Messages::getWarningMessage('Role', 'deactivate')
                                    ]
                                ]
                            ],
                            [
                                'label' => '<i class="fa fa-check-circle" aria-hidden="true"></i> Activate',
                                'url' => '#',
                                'id' => '',
                                'visible' => !$isActive,
                                'options' => [
                                    'class' => 'edit-status',
                                    'data' => [
                                        'title' => 'Activate Role',
                                        'toggle' => 'modal',
                                        'target' => '#editStatus',
                                        'url' => Url::toRoute("update-role-status?roleId={$role->id}"),
                                        'status' => Status::STATUS_ACTIVE,
                                        'msg' => Messages::getWarningMessage('Role', 'activate')
                                    ]
                                ]
                            ],
                            [
                                'label' => '<i class="fa fa-trash" aria-hidden="true"></i> Delete',
                                'url' => '#',
                                'id' => '',
                                'options' => [
                                    'class' => 'edit-status',
                                    'data' => [
                                        'title' => 'Delete Role',
                                        'toggle' => 'modal',
                                        'target' => '#editStatus',
                                        'url' => Url::toRoute("delete-role?roleId={$role->id}"),
                                        'status' => Status::STATUS_DELETED,
                                        'msg' => Messages::getWarningMessage('Role', 'delete')
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
echo Html::endTag('section');

//TOGGLE VIOLATION STATUS
echo ConfirmStatusUpdateModal::widget([
    'modalId' => 'editStatus',
    'footerCancel' => Messages::NO,
    'footerSubmit' => Messages::YES,
    'model' => new Role(),
]);
//New Role Modal
echo Html::beginTag('div', ['class' => 'container ']);
echo AdministrationRoleModalWidget::widget([
        'modalId' => 'modal',
        'model' => new Role(),
        'title' => 'New Role',
        'route' => 'create-roles',
        'formClass' => 'add-form',
        'classNames' => 'admin_role_modal',
        'placeHolder' => 'Enter role name',
        'btnWidthHeight' => ' mx-auto role_save_btn',
        'footerSubmit' => 'CREATE',
        'btnWidthHeights' => 'invisible',
    ]
);
echo Html::endTag('div');
//Edit Role Modal
echo Html::beginTag('div', ['class' => 'container ']);
echo AdministrationRoleModalWidget::widget([
        'modalId' => 'new_role_modal',
        'model' => new Role(),
        'title' => 'Edit Role',
        'genericModal' => true,
        'populateFields' => true,
        'classNames' => 'admin_role_modal',
        'placeHolder' => 'Enter role name',
        'btnWidthHeight' => ' mx-auto role_save_btn',
        'footerSubmit' => 'SAVE',
        'btnWidthHeights' => 'invisible',
    ]
);
echo Html::endTag('div');
