<?php

namespace app\jobs;

use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

/**
 * Class EmailJob
 * @package app\jobs
 */
class EmailJob extends BaseObject implements JobInterface
{
    public $emailTemplate;
    public $emailTemplateParams;
    public $toEmail;
    public $fromEmail;
    public $fromName;
    public $subject;
    public $attachment;
    public $fileName;
    public $contentType;

    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        $email = Yii::$app->mailer->compose($this->emailTemplate, $this->emailTemplateParams)
            ->setTo($this->toEmail)
            ->setFrom([$this->fromEmail => $this->fromName])
            ->setSubject($this->subject);
        if ($this->attachment) {
            $email->attachContent($this->attachment, [
                'fileName' => $this->fileName,
                'contentType' => $this->contentType
            ]);
        }

        return $email->send();
    }
}
