<?php

use app\constants\Messages;
use app\models\Admin;
use app\models\Status;
use app\models\UserCredential;
use app\widgets\modals\ConfirmStatusUpdateModal;
use app\widgets\modals\UsersViewFormWidget;
use app\models\forms\UsersTypesForm;
use app\widgets\CustomGridViewWidget;
use CottaCush\Yii2\Widgets\ActionButtons;
use yii\helpers\Html;
use app\widgets\ListTitleWidget;
use yii\helpers\Url;

//Header
echo ListTitleWidget::widget(['title' => 'USERS', 'btnClass' => ' invisible', 'breadcrumb' => 'users']);
//Table
echo Html::beginTag('section', ['class' => 'table_report']);
echo CustomGridViewWidget::widget([
        'emptyStateIcon' => 'assignment_ind',
        'emptyStateDescription' => 'No Users have been added yet',
        'emptyStateTitle' => 'Users',
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => 'S/N',
            ],
            'fullName:text:Name',
            'userEmail.email:email:Email',
            'role.label:text:Role',
            'statusHtml:html:Status',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Action',
                'template' => '{actions}',
                'buttons' => [
                    'actions' => function ($url, Admin $user, $key) {
                        $isActive = $user->status === Status::STATUS_ACTIVE;
                        $actions = [
                            [
                                'label' => '<i class="fa fa-pencil" aria-hidden="true"></i> Edit',
                                'url' => '#',
                                'options' => [
                                    'class' => 'edit-violation',
                                    'data' => [
                                        'title' => 'Edit User',
                                        'toggle' => 'modal',
                                        'target' => '#user_modal',
                                        'url' => Url::toRoute(
                                            "edit-user?userId={$user->id}&authId={$user->user_auth_id}"
                                        ),
                                        'first_name' => $user->first_name,
                                        'user_auth_id' => $user->user_auth_id,
                                        'last_name' => $user->last_name,
                                        'email' => $user->userEmail->email,
                                        'role' => $user->role_key,
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
                                        'title' => 'Activate User',
                                        'toggle' => 'modal',
                                        'target' => '#editStatus',
                                        'url' => Url::toRoute("update-admin-user-status?userId={$user->id}"),
                                        'status' => Status::STATUS_ACTIVE,
                                        'msg' => Messages::getWarningMessage('User', 'activate')
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
                                        'title' => 'Deactivate User',
                                        'toggle' => 'modal',
                                        'target' => '#editStatus',
                                        'url' => Url::toRoute("update-admin-user-status?userId={$user->id}"),
                                        'status' => Status::STATUS_INACTIVE,
                                        'msg' => Messages::getWarningMessage('User', 'deactivate')
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
//Edit User Modal
echo UsersViewFormWidget::widget([
        'modalId' =>'user_modal',
        'model' => new UsersTypesForm(),
        'title' => 'Edit User',
        'genericModal' => true,
        'populateFields' => true,
        'btnWidthHeight' => 'btn invite-btn',
        'btnWidthHeights' => 'btn cancel-btn',
        'footerSubmit' => 'SAVE',
        'footerCancel' => 'CANCEL',
        'userCredential' => new UserCredential(),
    ]
);
//TOGGLE VIOLATION STATUS
echo ConfirmStatusUpdateModal::widget([
    'modalId' => 'editStatus',
    'footerCancel' => Messages::NO,
    'footerSubmit' => Messages::YES,
    'model' => new Admin(),
]);
