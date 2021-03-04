<?php

namespace app\controllers;

use Abraham\TwitterOAuth\TwitterOAuth;
use app\constants\Messages;
use app\exceptions\EntityNotSavedException;
use app\libs\Utils;
use app\models\BaseModel;
use app\models\forms\ReportTypesForm;
use app\models\twitter\TwitterMessage;
use app\models\twitter\TwitterReport;
use yii\helpers\ArrayHelper;

class TwitterController extends BaseController
{
    public $enableCsrfValidation = false;

    public function actionWebhook()
    {
        if (isset($_REQUEST['crc_token'])) {
            $consumerSecret = getenv("API_SECRET");
            $signature = hash_hmac('sha256', $_REQUEST['crc_token'], $consumerSecret, true);
            $response['response_token'] = 'sha256=' . base64_encode($signature);
            header('Content-Type:application/json;charset=utf-8');
            http_response_code(200);
            echo json_encode($response);
        } else {
            $postData = $this->getRequest()->post();
            $messageDetails = $postData['direct_message_events'][0]['message_create'];
            $text = $messageDetails['message_data']['text'];
            $senderId = $messageDetails['sender_id'];
            $recipientId = $messageDetails['target']['recipient_id'];
            $sender = $postData['users'][$senderId];
            $name = $sender['name'];
            $twitterHandle = $sender['screen_name'];
            $location = $sender['location'];
            $now = Utils::getCurrentDateTime();
            $twitterMessage = new TwitterMessage([
                'sender_id' => $senderId,
                'recipient_id' => $recipientId,
                'message' => $text,
                'timestamp' => $now
            ]);
            $twitterReport = TwitterReport::findOne(['sender_id' => $senderId]);
            if ($twitterReport) {
                $twitterMessage->saveData();
            } else {
                $twitterUserData = new TwitterReport([
                    'twitter_handle' => $twitterHandle,
                    'name' => $name,
                    'location' => $location,
                    'sender_id' => $senderId,
                    'created_at' => $now,
                    'updated_at' => $now
                ]);
                $transaction = BaseModel::getDb()->beginTransaction();
                try {
                    $twitterUserData->saveData();
                    $twitterMessage->saveData();
                    $report = new ReportTypesForm([
                        'source' => 'twitter',
                        'twitter_report_id' => $twitterUserData->id
                    ]);
                    $report->saveData();
                    $transaction->commit();
                } catch (EntityNotSavedException $ex) {
                    $transaction->rollBack();
                    return $ex;
                }
            }
        }
    }

    public function actionSendTwitterReply()
    {
        $postData = $this->getRequest()->post();
        $message = ArrayHelper::getValue($postData, 'ReportTypesForm.report_reply');
        $id = ArrayHelper::getValue($postData, 'ReportTypesForm.id');
        $consumerKey = getenv('API_KEY');
        $consumerSecret = getenv('API_SECRET');
        $accessToken = getenv('ACCESS_TOKEN');
        $accessTokenSecret = getenv('ACCESS_TOKEN_SECRET');
        $now = Utils::getCurrentDateTime();
        $recipientInfo = ReportTypesForm::find()
            ->joinWith('twitterReports')
            ->where(['reports.twitter_report_id' => $id])
            ->one();
        $recipient = $recipientInfo->twitterReports->sender_id;
        $twitterMessage = new TwitterMessage([
            'sender_id' => getenv('SENDER_ID'),
            'recipient_id' => $recipient,
            'message' => $message,
            'timestamp' => $now
        ]);
        $transaction = BaseModel::getDb()->beginTransaction();
        try {
            $connection = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
            $data = [
                'event' => [
                    'type' => 'message_create',
                    'message_create' => [
                        'target' => [
                            'recipient_id' => $recipient
                        ],
                        'message_data' => [
                            'text' => $message
                        ]
                    ]
                ]
            ];
            $connection->post('direct_messages/events/new', $data, true);
            if (!($connection->getLastHttpCode() == 200)) {
                return $this->returnError(Messages::getFailureMessage('Reply', 'sent'));
            }
            $twitterMessage->saveData();
            $transaction->commit();
            return $this->returnSuccess(Messages::getSuccessMessage('Reply', 'sent'));
        } catch (EntityNotSavedException $ex) {
            $transaction->rollback();
            return $this->returnError(Messages::getFailureMessage('Reply', 'sent'));
        }
    }
}
