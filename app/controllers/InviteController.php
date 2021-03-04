<?php

namespace app\controllers;

use app\constants\Messages;
use app\exceptions\EntityNotSavedException;
use app\exceptions\EntityNotUpdatedException;
use app\exceptions\InviteCreationException;
use app\models\forms\InviteTypesForm;
use app\models\Permissions;
use yii\data\ActiveDataProvider;

class InviteController extends BaseController
{
    public function beforeAction($action)
    {
        $this->canAccess(Permissions::MANAGE_INVITE);
        return parent::beforeAction($action);
    }
    /**
     * @return string
     * @author femi meduna <femimeduna@gmail.com>
     * @handles Loading invite to view
     */
    public function actionIndex()
    {
        $this->layout = 'dashboard';
        $model = new InviteTypesForm();
        $query = InviteTypesForm::find()
            ->joinWith(['role']);

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => [
                    'pageSize' => 10,
                ],
                'sort' => [
                    'defaultOrder' => ['created_at' => SORT_DESC],
                    'attributes' => ['created_at', 'status'],
                ],
            ]
        );
        return $this->render('invite-types', ['model' => $model, 'dataProvider' => $dataProvider]);
    }

    /**
     * @return string
     * @author femi meduna <femimeduna@gmail.com>
     * @handles Sending invite
     */
    public function actionSendInvite()
    {
        $postData = $this->getRequest()->post();
        $invite = new InviteTypesForm();

        try {
            $invite->createInvite($postData);
            return $this->returnSuccess('Invitation successfully sent');
        } catch (InviteCreationException $ex) {
            return $this->returnError(Messages::INVITE_ALREADY_SENT);
        } catch (EntityNotSavedException $ex) {
            return $this->returnError(Messages::INVALID_EMAIL_ADDRESS);
        }
    }

    /**
     * @return string
     * @author femi meduna <femimeduna@gmail.com>
     * @handles resending invites
     */
    public function actionResendInvite($inviteId)
    {
        try {
            InviteTypesForm::resendInvite($inviteId);
            return $this->returnSuccess(Messages::getSuccessMessage('Invitation', 'sent'));
        } catch (InviteCreationException $ex) {
            return $this->returnError(Messages::getFailureMessage('Invitation', 'sent'));
        }
    }

    /**
     * @return string
     * @author femi meduna <femimeduna@gmail.com>
     * @handles Cancelling invites
     */
    public function actionUpdateInviteStatus($inviteId)
    {
        try {
            $this->updateModelStatus($inviteId, InviteTypesForm::class, 'Invitation');
            return $this->returnSuccess(Messages::getSuccessMessage('Invitation', 'cancelled'));
        } catch (EntityNotUpdatedException $ex) {
            return $this->returnError($ex->getMessage());
        }
    }
}
