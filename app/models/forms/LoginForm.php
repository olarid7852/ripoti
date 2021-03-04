<?php

namespace app\models\forms;

use app\models\User;
use Yii;
use yii\base\Model;

class LoginForm extends BaseFormModel
{
    public $email;
    public $password;
    public $rememberMe;

    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['email', 'email'],
            ['rememberMe', 'boolean'],
        ];
    }

    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    public function getUser()
    {
        if ($this->_user === false) {
            return User::findByUserEmail($this->email);
        }
        return $this->_user;
    }
}
