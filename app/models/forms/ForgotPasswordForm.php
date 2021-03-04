<?php

namespace app\models\forms;

use app\constants\EmailTemplates;
use app\constants\Messages;
use app\exceptions\EmailVerificationException;
use app\jobs\executors\EmailExecutor;
use app\models\EmailVerification;
use app\models\ResetPassword;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class ForgotPasswordForm extends BaseFormModel
{
    public $email;

    public function rules()
    {
        return [
            [['email'], 'required', 'message' => 'Email address cannot be blank.'],
            ['email', 'email', 'message' => ' Enter a valid email address' ],
        ];
    }

    public function attributeLabels()
    {
        return 
            ['email'=>''];
    }

    /**
     * @param $user
     * @throws EmailVerificationException
     * @throws \app\exceptions\PasswordResetTokenCreationException
     * @author Maryfaith Mgbede <adaamgbede@gmail.com>
     */
    public function forgotPasswordEmail($user)
    {
        $emailVerification = new EmailVerification();
        $verificationToken = ResetPassword::generateToken($user->id);
        $emailVerification->user_auth_id = $user->id;
        $emailVerification->token = $verificationToken;
        if (!$emailVerification->save()) {
            throw new EmailVerificationException(Messages::COULD_NOT_SEND_PASSWORD_RESET_EMAIL);
        }

        $baseUrl = ArrayHelper::getValue(Yii::$app->params, 'baseUrl');
        $emailParams = [
            'emailTemplate' => EmailTemplates::TEMPLATE_FORGOT_PASSWORD,
            'recipient' => $user->email,
            'subject' => EmailTemplates::SUBJECT_FORGOT_PASSWORD,
            'emailTemplateParams' => [
                'fullName' => $user,
                'verificationLink' => $baseUrl . 'default/reset-password?token=' . $verificationToken
            ]
        ];

        EmailExecutor::trigger($emailParams);
    }
}