<?php

namespace app\controllers;

use app\constants\Messages;
use app\constants\Status;
use app\exceptions\EntityNotSavedException;
use app\exceptions\EntityNotUpdatedException;
use app\constants\Source;
use app\libs\Utils;
use app\models\Admin;
use app\models\BaseModel;
use app\models\forms\CasesTypesForm;
use app\models\forms\ReportForm;
use app\models\forms\ReportTypesForm;
use app\models\EmailReport;
use app\models\Permissions;
use app\models\EmailMessage;
use app\models\twitter\TwitterMessage;
use app\models\twitter\TwitterReport;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use Yii;

class ReportController extends BaseController
{
    public function beforeAction($action)
    {
        $this->canAccess(Permissions::MANAGE_REPORT);
        return parent::beforeAction($action);
    }
    /**
     * @return string
     * @author femi meduna <femimeduna@gmail.com>
     * @handles loading reports to view
     */
    public function actionIndex($dateRange = null, $violation = null, $country = null, $source = null, $status = null)
    {
        $this->layout = 'dashboard';
        $model = new ReportTypesForm();
        $data = $this->getRequest()->get();
        $query = ReportTypesForm::getReportQuery($data);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ]
        ]);
        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'dateRange' => $dateRange,
            'violation' => $violation,
            'source' => $source,
            'status' => $status,
            'country' => $country,
        ]);
    }

    /**
     * @return string
     * @author femi meduna <femimeduna@gmail.com>
     * @handles loading selected report to view
     */
    public function actionViewReport($reportId)
    {
        $this->layout = 'dashboard';
        $model = new ReportTypesForm();
        $report = ReportTypesForm::find()
            ->where([ReportTypesForm::tableName() . '.id' => $reportId])
            ->joinWith(['formReports', 'twitterReports', 'emailReports'])
            ->one();
        if ($report->source == Source::SOURCE_TWITTER) {
            $reporter = $report->twitterReports->sender_id;
            $reportText = TwitterMessage::getMessages($reporter);
            return $this->render('view-report', [
                'model' => $model,
                'report' => $report,
                'reportText' => $reportText
            ]);
        }
        if ($report->source == Source::SOURCE_EMAIL) {
            $reporter = $report->emailReports->reporter_email;
            $reportText = EmailMessage::getMessages($reporter);
            return $this->render('view-report', [
                'model' => $model,
                'report' => $report,
                'reportText' => $reportText
            ]);
        }
        return $this->render('view-report', [
            'model' => $model,
            'report' => $report,
        ]);
    }

    /**
     * @return string
     * @author femi meduna <femimeduna@gmail.com>
     * handles updating report status
     */
    public function actionUpdateReportStatus($reportId)
    {
        $report = ReportTypesForm::findOne($reportId);
        if (empty($report->violation_type_id)) {
            return $this->returnError('Please add a violation type');
        }
        try {
            $this->updateModelStatus($reportId, ReportTypesForm::class, Messages::UPDATE_REPORT);
            return $this->returnSuccess(
                Messages::getSuccessMessage(
                    Messages::UPDATE_REPORT . ' status',
                    'changed'
                )
            );
        } catch (EntityNotUpdatedException $ex) {
            return $this->returnError($ex->getMessage());
        }
    }

    /**
     * @param $adminId
     * @return array
     * Dependant select of AdminUser based on roles
     */
    public function actionAdmin($adminId)
    {
        if (!$this->getRequest()->isAjax) {
            $this->redirect($this->getRequest()->getReferrer());
        }
        $adminUser = Admin::getAdminFromRole($adminId);
        return $this->sendSuccessResponse($adminUser);
    }

    /**
     * @param $reportId
     * @return string
     * @author meduna femi <femimeduna@gmail.com>
     * @handles creating report case view
     */
    public function actionCreateCase($reportId)
    {
        $this->layout = 'dashboard';
        $report = ReportTypesForm::findOne($reportId);
        if (empty($report->violation_type_id) &&
            ($report->source == Source::SOURCE_TWITTER || $report->source == Source::SOURCE_EMAIL)) {
            return $this->returnError('Please add a violation type');
        }
        $report->status = Status::STATUS_CASE;
        $case = new CasesTypesForm([
            'case_id' => 'DRP' . ' - ' . $reportId,
            'reporter_id' => $reportId,
            'created_at' => Utils::getCurrentDateTime()
        ]);
        if (CasesTypesForm::checkDuplicateReport($reportId)) {
            return $this->returnError(Messages::CASE_EXIST);
        } else {
            try {
                $report->saveData();
                $case->saveData();
                $this->returnSuccess(Messages::getSuccessMessage('case', 'created'));
            } catch (EntityNotSavedException $ex) {
                $this->returnError(Messages::getFailureMessage('case', 'saved'));
            }
            return $this->redirect('/case/index');
        }
    }

    /**
     * @param $reportId
     * @return \yii\web\Response
     * @handles Edit violation-types for twitter/email sources
     */
    public function actionEditViolation($reportId)
    {
        $postData = $this->getRequest()->post();
        $violation = ArrayHelper::getValue($postData, 'TwitterReport.violation_type_id');
        $dataConsent = ArrayHelper::getValue($postData, 'TwitterReport.data_consent');
        $country = ArrayHelper::getValue($postData, 'TwitterReport.country_id');
        $reporterData = ReportTypesForm::findOne(['twitter_report_id' => $reportId]);
        $reporterData->data_consent = $dataConsent;
        $reporterData->violation_type_id = $violation;
        try {
            $reporterData->saveData();
            $this->editModelData($reportId, TwitterReport::class, [
                'country_id' => $country,
                'updated_at' => Utils::getCurrentDateTime(),
            ]);
        } catch (EntityNotSavedException $ex) {
            return $this->returnError($ex->getMessage());
        }
        return $this->returnSuccess(Messages::getSuccessMessage('Report', 'updated'));
    }

    public function actionEmailViolation($reportId)
    {
        $postData = $this->getRequest()->post();
        $violation = ArrayHelper::getValue($postData, 'EmailReport.violation_type_id');
        $dataConsent = ArrayHelper::getValue($postData, 'EmailReport.data_consent');
        $country = Arrayhelper::getValue($postData, 'EmailReport.country_id');
        $reporterData = ReportTypesForm::findOne(['email_report_id' => $reportId]);
        $reporterData->data_consent = $dataConsent;
        $reporterData->violation_type_id = $violation;
        try {
            $reporterData->save();
            $this->editModelData($reportId, EmailReport::class, [
                'country_id' => $country,
                'updated_at' => Utils::getCurrentDateTime(),
            ]);

        } catch (EntityNotSavedException $ex) {
            return $this->returnError($ex->getMessage());
        }
        return $this->returnSuccess(Messages::getSuccessMessage('Report', 'updated'));
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\db\Exception
     * @author medinatapanpa@gmail.com
     */
    public function actionReplyReport()
    {
        $postData = $this->getRequest()->post();
        $id = ArrayHelper::getValue($postData, 'ReportTypesForm.id');
        $recipientInfo = ReportTypesForm::findOne(['form_report_id' => $id]);
        $recipient = $recipientInfo->formReports->email;
        $userName = ArrayHelper::getValue($postData, 'ReportTypesForm.first_name');
        $reportReply = ArrayHelper::getValue($postData, 'ReportTypesForm.report_reply');
        $reply = ReportForm::findOne($id);
        $reply->report_reply = $reply->report_reply . ',' . $reportReply;
        $transaction = BaseModel::getDb()->beginTransaction();
        try {
            $reply->saveData($reportReply);
            ReportForm::sendReportReplyEmail($recipient, $userName, $reportReply);
            $transaction->commit();
        } catch (EntityNotSavedException $ex) {
            $transaction->rollBack();
            return $this->returnError($ex->getMessage());
        }
        return $this->returnSuccess(Messages::getSuccessMessage('Report', 'reply sent'));
    }

    public function actionSendEmailReply()
    {
        $postData = $this->getRequest()->post();
        $message = ArrayHelper::getValue($postData, 'ReportTypesForm.report_reply');
        $id = ArrayHelper::getValue($postData, 'ReportTypesForm.id');
        $recipientInfo = ReportTypesForm::find()
            ->joinWith('emailReports')
            ->where(['reports.email_report_id' => $id])
            ->one();
        $recipient = $recipientInfo->emailReports->reporter_email;
        $emailMessage = new EmailMessage([
            'sender_id' => ArrayHelper::getValue(Yii::$app->params, 'adminEmail'),
            'recipient_id' => $recipient,
            'message' => $message,
            'timestamp' => Utils::getCurrentDateTime()
        ]);
        $transaction = BaseModel::getDb()->beginTransaction();
        try {
            $emailMessage->saveData();
            EmailMessage::sendReportReplyEmail($message, $recipient);
            $transaction->commit();
        } catch (EntityNotSavedException $ex) {
            $transaction->rollBack();
            return $this->returnError($ex->getMessage());
        }
        return $this->returnSuccess(Messages::getSuccessMessage('Report', 'reply sent'));
    }
}