<?php

namespace app\models\forms;

use app\constants\Messages;
use app\constants\MigrationConstants;
use app\constants\EmailTemplates;
use app\constants\Status;
use app\exceptions\EntityNotSavedException;
use app\libs\Utils;
use app\models\Admin;
use app\models\BaseModel;
use app\jobs\executors\EmailExecutor;
use app\models\cases\CaseAssignment;
use CottaCush\Yii2\Date\DateUtils;
use Yii;
use yii\db\conditions\InCondition;
use yii\helpers\ArrayHelper;

class CasesTypesForm extends BaseModel
{
    public $type;
    public $names;
    public $email;
    public $fullname;
    public $role;
    public $reply_case;
    public $ca_id;
    public $date_created;
    public $time_created;
    public $assignee_role;
    public $assignee_name;
    public $token;

    public static $statusOptions = [Status::STATUS_PENDING => 'Pending', Status::STATUS_RESOLVED => 'Resolved'];

    /**
     * @return string
     */
    public static function tableName()
    {
        return MigrationConstants::TABLE_REPORT_CASES;
    }

    public function getReportDetails()
    {
        return $this->hasOne(ReportTypesForm::class, ['id' => 'reporter_id']);
    }

    public static function checkDuplicateReport($reportId)
    {
        return self::find()->where(['reporter_id' => $reportId])->exists();
    }

    public function getCaseDetails()
    {
        return $this->hasOne(CaseAssignment::class, ['id' => 'case_id']);
    }

    /**
     * @param $data
     * @return \yii\db\ActiveQuery
     * @throws \Exception
     * @author femi meduna <femimeduna@gmail.com>
     * @handles filtering cases
     */
    public static function getCaseQuery($data)
    {
        $dateRange = ArrayHelper::getValue($data, 'date_range');
        $query = self::find()
            ->joinWith(['reportDetails', 'caseDetails']);
        $query = self::getDateRangeFilter($dateRange, $query, self::tableName());
        $query->andFilterWhere([
            CasesTypesForm::tableName() . '.status' => ArrayHelper::getValue($data, 'status'),
        ]);
        return $query;
    }

    public function getUserStatus()
    {
        $user = \Yii::$app->user->identity;
        return str_replace(
            '_',
            ' ',
            strtoupper($user->role_key === Admin::SUPER_ADMIN ? $this->status : $this->assigned_case_status)
        );
    }

    public static function getMonthlyCases()
    {
        $months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        $result = [];
        foreach ($months as $month) {
            $cases = self::find()
                ->where("MONTH(created_at) = $month")
                ->count();
            $result[] = [$month => $cases];
        }
        return $result;
    }

    public static function getMonthlyCasesCount()
    {
        $result = self::getMonthlyCases();
        $data = [];
        for ($index = 0; $index <= count($result); $index++) {
            $count = implode(', ', array_column($result, $index));
            array_push($data, $count);
        }
        return $data;
    }

    public function isSuperAdmin()
    {
        $user = \Yii::$app->user->identity;
        return $user->role_key === Admin::SUPER_ADMIN;
    }

    public function getCaseStatusHTML($column = 'assigned_case_status')
    {
        if (isset($this->$column)) {
            return Utils::getStatusHtml(str_replace('_', ' ', ucwords($this->$column)), '');
        }
        return null;
    }

    /**
     * @param $status
     * @param $ids
     * @throws EntityNotSavedException
     * @hanles update status of bulk actions
     */
    public static function updateBulkCases($status, $ids)
    {
        $execution = CasesTypesForm::updateAll(['status' => $status], new InCondition('id', 'IN', $ids));
        if (!$execution) {
            throw new EntityNotSavedException(Messages::getFailureMessage('Cases', 'updated'));
        }
    }

    /**
     * @param $assignee
     * @return mixed|string|null
     * @handles token generation
     * @author ireti <iretiogedengbe@gmail.com>
     */
    public static function generateToken($assignee)
    {
        $assigneeToken = new self();
        $assigneeToken->token = md5($assignee . '_' . time());
        return $assigneeToken->token;
    }

    public static function withdrawAssignedCaseEmail($userEmail, $user_name, $caseId, $summary)
    {
        $baseUrl = ArrayHelper::getValue(Yii::$app->params, 'baseUrl');
        $emailParams = [
            'emailTemplate' => EmailTemplates::REASSIGN_USER_TEMPLATE,
            'recipient' => $userEmail,
            'subject' => EmailTemplates::SUBJECT_REASSIGN_CASE,
            'emailTemplateParams' => [
                'name' => $user_name,
                'case' => $caseId,
                'summary' => $summary,
                'assigneeCaseLink' => $baseUrl . 'admin/login'
            ]
        ];

        EmailExecutor::trigger($emailParams);
    }

    /**
     * @param $userEmail
     * @param $userName
     * @param $caseId
     * @param $summary
     * @throws \Exception
     */
    public static function sendCaseReminderEmail($userEmail, $userName, $caseId)
    {
        $baseUrl = ArrayHelper::getValue(Yii::$app->params, 'baseUrl');
        $emailParams = [
            'emailTemplate' => EmailTemplates::REMINDER_CASE_TEMPLATE,
            'recipient' => $userEmail,
            'subject' => EmailTemplates::SUBJECT_REMINDER_CASE,
            'emailTemplateParams' => [
                'name' => $userName,
                'case' => $caseId,
                'assigneeCaseLink' => $baseUrl . '/admin/login'
            ]
        ];
        EmailExecutor::trigger($emailParams);
    }
}
