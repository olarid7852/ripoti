<?php

namespace app\controllers;

use app\constants\Messages;
use app\exceptions\EmailVerificationException;
use app\exceptions\EntityNotSavedException;
use app\exceptions\InviteTokenValidationException;
use app\exceptions\TokenExpiredException;
use app\libs\Utils;
use app\models\Admin;
use app\models\forms\InviteTypesForm;
use app\models\forms\SignUpForm;
use app\models\forms\ForgotPasswordForm;
use app\models\forms\LoginForm;
use app\models\forms\ResetPasswordForm;
use app\models\UserCredential;
use cottacush\userauth\exceptions\UserAuthenticationException;
use cottacush\userauth\models\ErrorMessages;
use Yii;
use yii\helpers\ArrayHelper;

class DefaultController extends BaseController
{
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect('/dashboard/index');
        }
        $model = new LoginForm();
        $postRequest = $this->getRequest()->post();

        if ($model->load($postRequest) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', ['model' => $model]);
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\base\InvalidConfigException
     * @author Maryfaith Mgbede <adaamgbede@gmail.com>
     */
    public function actionSignIn()
    {
        $login = new LoginForm();
        $postRequest = $this->getRequest()->post();
        $login->load($postRequest);
        $userCredential = new UserCredential();

        try {
            $userCredential = $userCredential->authenticate($login->email, $login->password);
            $adminUser = Admin::getByUserAuthId($userCredential->id);

            if (is_null($adminUser)) {
                return $this->returnError('Could not find an account with those credentials');
            }

            Admin::login($adminUser, $this->getModuleUser());
            return $this->redirect('/dashboard/index');
        } catch (UserAuthenticationException $ex) {
            return $this->returnError('Incorrect login credentials');
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect('login');
    }

    /**
     * @return string
     * @author ireti ogedengbe <iretiogedengbe@gmail.com>
     * @handles resetting password
     */
    //RESET PASSWORD
    public function actionResetPassword()
    {
        $token = $this->getRequest()->get('token');
        try {
            $id = UserCredential::getUserIdAndCheckTokenExpired($token);
            $model = new ResetPasswordForm(['user_id' => $id]);
        } catch (InviteTokenValidationException $e) {
            return $this->render('invalid-token');
        } catch (TokenExpiredException $e) {
            return $this->render('invalid-token');
        }
        return $this->render('reset-password', ['model' => $model]);
    }

    /**
     * @return string
     * @author ireti ogedengbe <iretiogedengbe@gmail.com>
     * @handles saving new password
     */
    //SAVE NEW PASSWORD
    public function actionSaveNewPassword()
    {
        $postData = $this->getRequest()->post();
        $userId = ArrayHelper::getValue($postData, 'ResetPasswordForm.user_id');
        $password = ArrayHelper::getValue($postData, 'password');
        $newPassword = ArrayHelper::getValue($postData, 'ResetPasswordForm.password');
        $user = UserCredential::findOne(['id' => $userId]);
        $user->password = Utils::encryptPassword($newPassword);
        try {
            $user->savePassword();
        } catch (EntityNotSavedException $ex) {
            return $this->returnError(ErrorMessages::PASSWORD_UPDATE_FAILED);
        }
        return $this->redirect('success-password');
    }

    public function actionForgotPassword()
    {
        $model = new ForgotPasswordForm();
        return $this->render('forgot-password', ['model' => $model]);
    }

    public function actionResetSent()
    {
        return $this->render('reset-sent');
    }

    public function actionSignupError()
    {
        return $this->render('signup-error');
    }

    public function actionSuccessPassword()
    {
        return $this->render('success-password');
    }

    /**
     * @param null $token
     * @return string|\yii\web\Response
     * @throws \Exception
     * @author femi meduna <femimeduna@gmail.com>
     * @handles user sign up
     */
    public function actionSignUp($token = null)
    {
        try {
            $invite = InviteTypesForm::validateToken($token);
            $model = new SignUpForm([
                'email' => ArrayHelper::getValue($invite, 'email'),
                'role_key' => ArrayHelper::getValue($invite, 'role_key'),
                'token' => $token
            ]);

            return $this->render('sign-up', ['model' => $model]);
        } catch (InviteTokenValidationException $ex) {
            return $this->render('signup-error');
        }
    }

    /**
     * @return \yii\web\Response
     * @throws \app\exceptions\PasswordResetTokenCreationException
     * @author Maryfaith Mgbede <adaamgbede@gmail.com>
     */
    public function actionSendForgotPasswordEmail()
    {
        $postData = $this->getRequest()->post();
        $forgotPasswordForm = new ForgotPasswordForm();
        $userCredential = new UserCredential();
        $forgotPasswordForm->load($postData);
        $email = ArrayHelper::getValue($postData, 'ForgotPasswordForm.email');
        $user = $userCredential->getUserByEmail($email);

        if (!$user) {
            return $this->returnError(Messages::getNotFoundMessage('Email'));
        }

        try {
            $forgotPasswordForm->forgotPasswordEmail($user);
            return $this->redirect('reset-sent');
        } catch (EmailVerificationException $ex) {
            return $this->returnError($ex->getMessage());
        }
    }
}
