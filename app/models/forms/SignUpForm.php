<?php

namespace app\models\forms;

use app\constants\MigrationConstants;
use app\models\Admin;
use app\models\BaseModel;
use app\models\Role;
use app\models\UserCredential;
use cottacush\userauth\models\UserType;

/**
 * Class SignUpForm
 * @package app\models\forms
 */
class SignUpForm extends BaseModel
{
    public $email;
    public $password;
    public $confirm_password;
    public $token;

    public static function tableName()
    {
        return MigrationConstants::TABLE_ADMINS;
    }

    public function rules()
    {
        return [
            [['email', 'last_name', 'first_name', 'password', 'confirm_password'], 'required'],
            ['email', 'email'],
            [['role_key', 'token'], 'safe'],
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'message' => 'Password mismatch'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Email Address',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'password' => 'New Password',
            'confirm_password' => 'Confirm Password',
        ];
    }

    public function getInviteeRole()
    {
        return $this->hasOne(Role::class, ['key' => 'role_key']);
    }

    public function getInviteeEmail()
    {
        return $this->hasOne(UserCredential::class, ['id' => 'user_auth_id']);
    }


    /**
     * @throws \cottacush\userauth\exceptions\UserCreationException
     * @author femi meduna <femimeduna@gmail.com>
     * @handles user creation
     */
    public function createAdminUser()
    {
        $userCredential = new UserCredential();
        $adminUser = new Admin();
        $userTypeId = UserType::findOne(['name' => UserCredential::USER_TYPE_ID_ADMIN]);
        $userAuthId = $userCredential->createUser(trim($this->email), $this->password, true, $userTypeId);
        $adminUser->registerUser($this->first_name, $this->last_name, $userAuthId, $this->role_key);
    }
}
