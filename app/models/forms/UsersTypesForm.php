<?php

namespace app\models\forms;

use app\constants\MigrationConstants;
use app\models\BaseModel;
use app\models\Role;
use app\models\UserCredential;

/**
 * Class UsersTypesForm
 * @package app\models\forms
 */
class UsersTypesForm extends BaseModel
{
    public $email;

    public static function tableName()
    {
        return MigrationConstants::TABLE_ADMINS;
    }

    public function rules()
    {
        return [[['first_name', 'last_name', 'role_key'], 'required'],
            [['status', 'email'], 'safe'],
            ['email', 'email'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     * @author femi meduna <femimeduna@gmail.com>
     */
    public function getUserEmail()
    {
        return $this->hasOne(UserCredential::class, ['id' => 'user_auth_id']);
    }

    public function attributeLabels()
    {
        return [
            'first_name' => 'First name :',
            'last_name' => 'Last name :',
            'email' => 'Email :',
            'role_key' => 'Role :',
        ];
    }
}