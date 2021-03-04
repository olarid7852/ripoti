<?php

namespace app\models;
use app\exceptions\PasswordResetTokenCreationException;
use CottaCush\Yii2\Date\DateUtils;
use app\constants\MigrationConstants;

/**
 * Class ResetPassword
 * @package app\models
 *
 * @property string token
 * @property integer user_id
 */
class ResetPassword extends BaseModel
{
    const EXPIRY_TIME = 86400;
    public static function tableName()
    {
        return MigrationConstants::TABLE_USER_PASSWORD_RESETS;
    }
    /**
     * @param $userId
     * @return string
     * @throws PasswordResetTokenCreationException
     * @author Toluwalase Akintoye <toluakintoye@gmail.com>
     */
    public static function generateToken($userId)
    {
        $resetPasswordToken = new self();
        $resetPasswordToken->token = md5($userId . '_' . time());
        $resetPasswordToken->user_id = $userId;
        $resetPasswordToken->date_requested = DateUtils::getMysqlNow();
        $resetPasswordToken->date_of_expiry = time() + self::EXPIRY_TIME;
        if (!$resetPasswordToken->save()) {
            throw new PasswordResetTokenCreationException($resetPasswordToken->getFirstError());
        }
        return $resetPasswordToken->token;
    }
}