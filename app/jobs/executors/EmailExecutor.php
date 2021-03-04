<?php

namespace app\jobs\executors;

use app\jobs\EmailJob;
use Yii;
use yii\helpers\ArrayHelper;

class EmailExecutor implements ExecutorInterface
{
    /**
     * @param array $executorData
     * @author Babatunde Otaru <tunde@cottacush.com>
     */
    public static function trigger($executorData = [])
    {
        $options = ArrayHelper::getValue($executorData, 'options', []);
        $adminEmail = ArrayHelper::getValue(Yii::$app->params, 'adminEmail');
        $adminName = ArrayHelper::getValue(Yii::$app->params, 'emailSenderName');
        Yii::$app->myqueue->push(new EmailJob([
            'emailTemplate' => ArrayHelper::getValue($executorData, 'emailTemplate'),
            'emailTemplateParams' => ArrayHelper::getValue($executorData, 'emailTemplateParams', []),
            'toEmail' => ArrayHelper::getValue($executorData, 'recipient'),
            'fromEmail' => ArrayHelper::getValue($options, 'sender', $adminEmail),
            'fromName' => ArrayHelper::getValue($options, 'senderName', $adminName),
            'subject' => ArrayHelper::getValue($executorData, 'subject'),
            'attachment' => ArrayHelper::getValue($options, 'attachment'),
            'fileName' => ArrayHelper::getValue($options, 'fileName'),
            'contentType' => ArrayHelper::getValue($options, 'contentType', 'application/pdf'),
        ]));
    }
}
