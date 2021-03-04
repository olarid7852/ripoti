<?php

namespace app\models\cases;

use app\constants\EmailTemplates;
use app\constants\MigrationConstants;
use app\constants\Status;
use app\jobs\executors\EmailExecutor;
use app\models\Admin;
use app\models\BaseModel;
use app\models\forms\CasesTypesForm;
use Yii;
use yii\helpers\ArrayHelper;

class CaseAssignment extends BaseModel
{
    public $assignee_role;
    public $reply_case;
    public $email;
    public $fullname;
    public $assignee_name;
    public $case_summary;

    public static function tableName()
    {
        return MigrationConstants::TABLE_ASSIGNED_CASES;
    }

    public function rules()
    {
        return [
            [['case_summary', 'assignee_role', 'assignee_name', 'case_id', 'assignee_id'], 'safe']
        ];
    }

    public function getAdminDetails()
    {
        return $this->hasOne(Admin::class, ['id' => 'assignee_id']);
    }

    public function isSuperAdmin()
    {
        $user = \Yii::$app->user->identity;
        return $user->role_key === Admin::SUPER_ADMIN;
    }

    public function getCaseDetails()
    {
        return $this->hasOne(CasesTypesForm::class, ['id' => 'case_id']);
    }

    public static function checkDuplicateAssignee($assigneeId, $caseId)
    {
        return self::find()->where(['assignee_id' => $assigneeId, 'case_id' => $caseId ])->exists();
    }

    public static function getAssignees($caseId)
    {
        $result = self::find()
            ->where(['case_id' => $caseId])
            ->andWhere(['!=', CaseAssignment::tableName() . '.status', Status::STATUS_WITHDRAW])
            ->joinwith('adminDetails')
            ->all();
        return Arrayhelper::map($result, 'id', 'adminDetails.fullName');
    }

    /**
     * @param $data
     * @return \yii\db\ActiveQuery
     * @throws \Exception
     * @author femi meduna <femimeduna@gmail.com>
     * @handles filtering cases
     */
    public static function getCaseQuery($data, $user)
    {
        $dateRange = ArrayHelper::getValue($data, 'date_range');
        $query = self::find()
            ->joinWith(['adminDetails', 'caseDetails']);
        $query = self::getDateRangeFilter($dateRange, $query, self::tableName());
        return $query;
    }

    /**
     * @param $assigneeEmail
     * @throws \Exception
     * @handles assignee email
     * @author ireti <iretiogedengbe@gmail.com>
     */
    public static function assignCaseEmail($assigneeEmail, $name, $caseId, $summary)
    {
        $baseUrl = ArrayHelper::getValue(Yii::$app->params, 'baseUrl');
        $emailParams = [
            'emailTemplate' => EmailTemplates::ASSIGNEE_INVITE_TEMPLATE,
            'recipient' => $assigneeEmail,
            'subject' => EmailTemplates::SUBJECT_ASSIGN_CASE,
            'emailTemplateParams' => [
                'name' => $name,
                'case' => $caseId,
                'summary' => $summary,
                'assigneeCaseLink' => $baseUrl . 'admin/login'
            ]
        ];

        EmailExecutor::trigger($emailParams);
    }
}