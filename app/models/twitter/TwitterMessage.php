<?php

namespace app\models\twitter;

use app\constants\MigrationConstants;
use app\models\BaseModel;

class TwitterMessage extends BaseModel
{
    public static function tableName()
    {
        return MigrationConstants::TABLE_TWITTER_CHAT_ROOM;
    }

    public static function getMessages($senderId)
    {
        return self::find()
            ->filterWhere(['recipient_id' => $senderId])
            ->orFilterwhere(['sender_id' => $senderId])
            ->orderBy(['timestamp' => SORT_ASC])
            ->all();
    }
}
