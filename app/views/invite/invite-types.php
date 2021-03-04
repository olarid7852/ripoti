<?php

use app\constants\Messages;
use app\constants\Status;
use app\widgets\CustomGridViewWidget;
use app\widgets\ListTitleWidget;
use app\widgets\modals\ConfirmStatusUpdateModal;
use CottaCush\Yii2\Widgets\ActionButtons;
use yii\helpers\Html;
use app\widgets\modals\InviteModalWidget;
use app\models\forms\InviteTypesForm;
use yii\helpers\Url;

//HEADER CONTENT
echo ListTitleWidget::widget(['title' => 'INVITES', 'btn_name' => 'Invite', 'breadcrumb' => 'Invite']);

//TABLE
echo Html::beginTag('section', ['class' => 'table_report ']);
echo CustomGridViewWidget::widget([
        'emptyStateIcon' => 'question_answer',
        'emptyStateDescription' => 'No Invitations have been made',
        'emptyStateTitle' => 'Invite',
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => 'S/N',
            ],
            'email:email:Email',
            'role.label:text:Role',
            'statusHtml:html:Status',
            'created_at:date:Date Created',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Action',
                'template' => '{actions}',
                'buttons' => [
                    'actions' => function ($url, InviteTypesForm $invite, $key) {
                        $actions = [
                            [
                                'label' => '<i class="fa fa-reply-all" aria-hidden="true"></i> Resend',
                                'url' => '#',
                                'id' => 'resend',
                                'options' => [
                                    'class' => 'edit-status',
                                    'data' => [
                                        'title' => 'Resend Invitation',
                                        'toggle' => 'modal',
                                        'target' => '#editStatus',
                                        'url' => Url::toRoute("resend-invite?inviteId={$invite->id}"),
                                        'status' => Status::STATUS_ACTIVE,
                                        'msg' => Messages::getWarningMessage('Invitation', 'resend')
                                    ]
                                ]
                            ],
                            [
                                'label' => '<i class="fa fa-times" aria-hidden="true"></i> Cancel',
                                'url' => '#',
                                'id' => 'cancel',
                                'options' => [
                                    'class' => 'edit-status',
                                    'data' => [
                                        'title' => 'Cancel Invitation',
                                        'toggle' => 'modal',
                                        'target' => '#editStatus',
                                        'url' => Url::toRoute("update-invite-status?inviteId={$invite->id}"),
                                        'status' => Status::STATUS_CANCELLED,
                                        'msg' => Messages::getWarningMessage('Invitation', 'cancel')
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
//end table

//modal
echo InviteModalWidget::widget([
        'modalId' => 'modal',
        'model' => new InviteTypesForm(),
        'title' => 'Invite New Users',
        'route' => 'send-invite',
        'placeHolder' => 'Enter email address',
        'classModalNames' => 'modal-body',
        'btnWidthHeight' => 'btn invite-btn',
        'btnWidthHeights' => ' btn cancel-btn',
        'footerCancel' => 'CANCEL',
        'footerSubmit' => 'SEND INVITE',
    ]
);

//CONFIRM ACTION MODAL
echo ConfirmStatusUpdateModal::widget([
    'modalId' => 'editStatus',
    'footerCancel' => Messages::NO,
    'footerSubmit' => Messages::YES,
    'model' => new InviteTypesForm(),
]);
