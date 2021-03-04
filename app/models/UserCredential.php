<?php

namespace app\models;

use app\exceptions\EntityNotSavedException;
use cottacush\userauth\models\User as AuthUserCredentials;
use cottacush\userauth\models\UserPasswordReset;
use UserNotExistsException;
use app\constants\Messages;
use app\constants\MigrationConstants;
use app\exceptions\InviteTokenValidationException;
use app\exceptions\TokenExpiredException;

/**
* This is the model class for table 'user_credentials'.
* @property integer $id
* @property string $email
* @property string $password
* @property integer $status
* @property string $created_at
* @property string $updated_at
* @property integer $user_type_id
*
*/

class UserCredential extends AuthUserCredentials
{
    const USER_TYPE_ID_ADMIN = 1;
    const USER_TYPE_ID_REPORTERS = 2;

    /**
    * @inheritdoc
    */

    public function rules()
    {
        return [
            [['email', 'password', 'created_at'], 'required'],
            [['user_type_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['email', 'password', 'status'], 'string', 'max' => 100],
            ['email', 'unique', 'message' => 'Sorry, The email has been used by another user'],
        ];
    }

    /**
    * @inheritdoc
    */

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'password' => 'Password',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'user_type_id' => 'User Type ID',
        ];
    }

    public static function tableName()
    {
        return MigrationConstants::TABLE_USER_CREDENTIALS;
    }

    public function isActive()
    {
        return ($this->status == Status::STATUS_ACTIVE);
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getUser()
    {
        return $this->hasOne(Admin::className(), ['user_auth_id' => 'id']);
    }

    /**
    * @param $email
    * @return UserCredential
    * @throws UserNotExistsException
    */
    public static function findUserByEmail($email)
    {
        $user = self::findOne(['email' => $email]);
        if (!$user) {
            throw new UserNotExistsException('User does not exist');
        }
        return $user;
    }
    
    /**
    * @param $token
    * @return int|null
    * @throws InviteTokenValidationException
    * @throws TokenExpiredException
    * @author Toluwalase Akintoye <toluakintoye@gmail.com>
    */
    public static function getUserIdAndCheckTokenExpired($token)
    {
        $tokenData = (new UserPasswordReset())->getTokenData($token);
        if (!$tokenData) {
            throw new InviteTokenValidationException(Messages::INVALID_PASSWORD_RESET_TOKEN);
        }
        if ($tokenData->date_of_expiry < time()) {
            throw new TokenExpiredException(Messages::PASSWORD_RESET_EXPIRED);
        }
        return $tokenData->user_id;
    }


    public function savePassword()
    {
        if (!$this->save()) {
            throw new EntityNotSavedException($this->getFirstError());
        }
    }
}
