<?php

namespace app\models\forms;

use app\constants\EmailTemplates;
use app\constants\Messages;
use app\constants\MigrationConstants;
use app\constants\Status;
use app\exceptions\InviteCreationException;
use app\exceptions\InviteTokenValidationException;
use app\jobs\executors\EmailExecutor;
use app\models\BaseModel;
use app\models\Role;
use CottaCush\Yii2\Date\DateUtils;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * InviteTypesForm Form is the model behind the invite types form.
 */
class InviteTypesForm extends BaseModel
{

    public static function tableName()
    {
        return MigrationConstants::TABLE_INVITES;
    }

    public function rules()
    {
        return
            [
                ['email', 'email'],
                [['email', 'role', 'status'], 'safe'],
            ];
    }

    public static function getInvites()
    {
        $result = InviteTypesForm::find()->all();
        return Arrayhelper::map($result, 'id', 'email');
    }

    public function getRole()
    {
        return $this->hasOne(Role::class, ['key' => 'role_key']);
    }

    /**
     * @param $invitee
     * @return mixed|string|null
     * @handles token generation
     * @author femi meduna <femimeduna@gmail.com>
     */
    public static function createInviteToken($invitee)
    {
        $invitationToken = new self();
        $invitationToken->token = md5($invitee . '_' . time());
        return $invitationToken->token;
    }

    /**
     * @param $inviteeEmail
     * @throws \Exception
     * @handles invite email
     * @author femi meduna <femimeduna@gmail.com>
     */
    public static function invitationEmail($inviteeEmail)
    {
        $invitationToken = InviteTypesForm::createInviteToken($inviteeEmail);
        $baseUrl = ArrayHelper::getValue(Yii::$app->params, 'baseUrl');
        $emailParams = [
            'emailTemplate' => EmailTemplates::INVITE_TEMPLATE,
            'recipient' => $inviteeEmail,
            'subject' => EmailTemplates::SUBJECT_INVITE,
            'emailTemplateParams' => [
                'invitationLink' => $baseUrl . '/default/sign-up?token=' . $invitationToken
            ]
        ];
        EmailExecutor::trigger($emailParams);
    }

    /**
     * @param $email
     * @return bool
     * @author femi meduna <femimeduna@gmail.com>
     * @handles email verification
     */
    public static function checkDuplicateInviteEmails($email)
    {
        return self::find()
            ->where(['email' => $email])
            ->andwhere('status != "' . Status::STATUS_CANCELLED . '"')->exists();
    }

    /**
     * @param $postData
     * @throws InviteCreationException
     * @throws \app\exceptions\EntityNotSavedException
     * @author femi meduna <femimeduna@gmail.com>
     * @handle create invite
     */
    public static function createInvite($postData)
    {
        $email = ArrayHelper::getValue($postData, 'InviteTypesForm.email');
        $role = ArrayHelper::getValue($postData, 'InviteTypesForm.role_key');
        $now = DateUtils::getMysqlNow();
        $token = InviteTypesForm::createInviteToken($email);
        $invite = new InviteTypesForm([
            'email' => $email,
            'role_key' => $role,
            'token' => $token,
            'created_at' => $now
        ]);

        if (InviteTypesForm::checkDuplicateInviteEmails($email)) {
            throw new InviteCreationException(Messages::INVITE_ALREADY_SENT);
        }
        $invite->saveData();
        $invite->invitationEmail($email);
    }

    /**
     * @param $inviteId
     * @throws \Exception
     * @author femi meduna <femimeduna@gmail.com>
     * @handles resend invite
     */
    public static function resendInvite($inviteId)
    {
        $invite = self::find()->where([self::tableName() . '.id' => $inviteId])->joinWith(['role'])->one();
        $invite->token = self::createInviteToken($invite->email);
        $invite->updated_at = DateUtils::getMysqlNow();
        $invite->status = Status::STATUS_PENDING;
        $invite->role_key = $invite['role_key'];
        $invite->saveData();
        (new InviteTypesForm())->invitationEmail($invite->email);
    }

    public function getToken($token)
    {
        return InviteTypesForm::find()->where(['token' => $token])->one();
    }
    /**
     * @param $token
     * @return array|\yii\db\ActiveRecord
     * @throws InviteTokenValidationException
     * @author femi meduna <femimeduna@gmail.com
     * @handles validation of token
     */
    public static function validateToken($token)
    {
        $invite = InviteTypesForm::find()->where(['token' => $token])
            ->joinWith(['role'])->one();
        if (!$invite) {
            throw new InviteTokenValidationException(Messages::INVITE_NOT_FOUND);
        }
        if ($invite->status == Status::STATUS_ACCEPTED) {
            throw new InviteTokenValidationException(Messages::INVITE_ALREADY_ACCEPTED);
        }

        return $invite;
    }

    /**
     * @throws \app\exceptions\EntityNotSavedException
     * @author femi meduna <femimeduna@gmail.com>
     * @handles accepting invitation
     */
    public function acceptInvite()
    {
        $this->status = Status::STATUS_ACCEPTED;
        $this->saveData();
    }
}
