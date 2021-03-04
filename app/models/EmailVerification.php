<?php

namespace app\models;

use app\constants\MigrationConstants;

/**
 * @property mixed|string|null token
 * @property mixed|null user_auth_id
 */
class EmailVerification extends BaseModel
{
    const EMAIL_VERIFICATION_LINK_EXPIRY_TIME_IN_SECONDS = 259200; // 3 days
    const FORGOT_PASSWORD_LINK_EXPIRY_TIME_IN_SECONDS = 7200; // 2 hours

    public static function tableName()
    {
        return MigrationConstants::TABLE_EMAIL_VERIFICATIONS;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAuth()
    {
        return $this->hasOne(UserCredential::class, ['id' => 'user_auth_id']);
    }

    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     */
    public function generateVerificationToken()
    {
        $this->token = md5(time());
        return $this->token;
    }
}
