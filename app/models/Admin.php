<?php

namespace app\models;

use app\constants\MigrationConstants;
use app\constants\Status;
use cottacush\userauth\exceptions\UserCreationException;
use CottaCush\Yii2\Date\DateUtils;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property integer $user_auth_id
 * @property string $created_at
 * @property string $role_key
 * @property string $status
 * @property integer $is_active
 * @property string $updated_at
 * @property integer $store_id
 * @property integer $updated_by
 *
 * @property Role $role
 * @property Status $statusObj
 * @property UserCredential $userAuth
 */
class Admin extends BaseModel implements IdentityInterface
{
    const SESSION_KEY = 'admin_user';
    const SECONDS_IN_A_MONTH = 2592000;
    const SUPER_ADMIN = 'system-administrator';
    public $names;
    public $counts;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return MigrationConstants::TABLE_ADMINS;
    }

    public function rules()
    {
        return [['status', 'safe']];
    }

    public function getUserCredential()
    {
        return $this->hasOne(UserCredential::class, ['id' => 'user_auth_id']);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        if (!Yii::$app->session->has(self::SESSION_KEY)) {
            return null;
        }
        $user = unserialize(Yii::$app->session->get(self::SESSION_KEY));
        return $user ?: null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->user_auth_id;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->user_auth_id === $authKey;
    }

    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::class, ['key' => 'role_key']);
    }

    public static function getAdminFromRole($role_key)
    {
        $result = self::find()->where(['role_key' => $role_key])->orderBy(['first_name' => SORT_ASC])->all();
        return ArrayHelper::map($result, 'id', 'fullName');
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusObj()
    {
        return $this->hasOne(Status::class, ['key' => 'status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAuth()
    {
        return $this->hasOne(UserCredential::className(), ['id' => 'user_auth_id']);
    }

    /**
     * Confirm that user account is active
     * @return bool
     */
    public function isActive()
    {
        return $this->status == Status::STATUS_ACTIVE;
    }

    /**
     * Confirm that userauth credentials is active
     * @return bool
     */
    public function isCredentialActive()
    {
        return $this->userAuth->isActive();
    }

    public function getAvatar()
    {
        return '';
    }

    public static function getByUserAuthId($userAuthId)
    {
        /** @var self $user */
        $user = self::find()->where(['user_auth_id' => $userAuthId])->limit(1)->one();
        return ($user) ? $user : null;
    }

    /**
     * @param $identity
     * @param \yii\web\User $moduleUser
     */
    public static function login($identity, $moduleUser)
    {
        Yii::$app->session->set(self::SESSION_KEY, serialize($identity));
        $moduleUser->login($identity, self::SECONDS_IN_A_MONTH);
    }

    /**
     * @param $firstName
     * @param $lastName
     * @param $userAuthId
     * @param $role
     */
    public function registerUser($firstName, $lastName, $userAuthId, $role)
    {
        $this->first_name = $firstName;
        $this->last_name = $lastName;
        $this->user_auth_id = $userAuthId;
        $this->role_key = $role;
        $this->status = Status::STATUS_ACTIVE;
        $this->created_at = DateUtils::getMysqlNow();
        if (!$this->save()) {
            throw new UserCreationException($this->getFirstError());
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     * @author femi meduna <femimeduna@gmail.com>
     */
    public function getUserEmail()
    {
        return $this->hasOne(UserCredential::class, ['id' => 'user_auth_id']);
    }
}
