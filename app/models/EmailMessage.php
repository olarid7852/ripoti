<?php

namespace app\models;

use app\constants\MigrationConstants;
use app\jobs\executors\EmailExecutor;
use app\constants\EmailTemplates;

class EmailMessage extends BaseModel
{
    private $sender_id;
    private $recipient_id;
    private $message;
    private $timestamp;

    public static function tableName()
    {
        return MigrationConstants::TABLE_EMAIL_MESSAGES;
    }

    public static function getMessages($senderId)
    {
        return self::find()
            ->filterWhere(['recipient_id' => $senderId])
            ->orFilterwhere(['sender_id' => $senderId])
            ->orderBy(['timestamp' => SORT_ASC])
            ->all();
    }

    public static function sendReportReplyEmail($message, $recipient)
    {
        $emailParams = [
            'emailTemplate' => EmailTemplates::EMAIL_REPLY_TEMPLATE,
            'recipient' => $recipient,
            'subject' => EmailTemplates::SUBJECT_EMAIL_REPLY,
            'emailTemplateParams' => [
                'reportReply' => $message,
            ]
        ];
        EmailExecutor::trigger($emailParams);
    }
}