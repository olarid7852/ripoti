<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use cottacush\userauth\models\BaseModel;
use yii\console\Controller;
use app\models\EmailReport;
use app\models\EmailMessage;
use app\libs\Utils;
use app\models\forms\ReportTypesForm;
use ZBateson\MailMimeParser\Message;

/**
 * Class EmailController
 * @package app\commands
 * @author Ireti Ogedengbe
 */
class EmailController extends Controller
{
    public function actionPipe($body)
    {
        $message = Message::from($body);
        $message_text = $message->getTextContent();
        $email_sender = $message->getHeader('From')->getValue();
        $email_recipient = $message->getHeader('To')->getValue();

        $report = new EmailMessage();
        $report->sender_id = $email_sender;
        $report->recipient_id = $email_recipient;
        $report->message = $message_text;
        $report->timestamp = Utils::getCurrentDateTime();
        $emailReport = EmailReport::findOne(['reporter_email' => $email_sender]);

        if ($emailReport) {
            $report->saveData();
        } else {
            $transaction = BaseModel::getDb()->beginTransaction();
            try {
                $ripoti = new EmailReport();
                $ripoti->reporter_email = $email_sender;
                $ripoti->created_at = Utils::getCurrentDateTime();
                $ripoti->save();
                $report->saveData();
                $report = new ReportTypesForm(['source' => 'email', 'email_report_id' => $ripoti->id]);
                $report->save();
                $transaction->commit();
                return true;
            } catch (\Exception $ex) {
                $transaction->rollback();
                return false;
            }
        }
    }
}