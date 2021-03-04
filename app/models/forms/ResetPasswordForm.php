<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;

/**
 * Resetpassword Form is the model behind the reset password form.
 */
class ResetPasswordForm extends BaseFormModel
{
    public $password;
    public $confirm_password;
    public $user_id;

    /**
     * @return array the validation rules.
    */
    public function rules()
    {
        return [
            // new password and confirm password are both required
            [['password', 'confirm_password'], 'required',],
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'message' => 'Password mismatch'] ,
        ];
    }

    public function attributeLabels()
    {
        return [
           'password' => 'New Password',
           'confirm_password' => 'Confirm new Password',
        ];
    }
}