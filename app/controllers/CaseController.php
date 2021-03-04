<?php

namespace app\controllers;

use app\constants\Messages;
use app\constants\Status;
use app\exceptions\EntityNotSavedException;
use app\libs\Utils;
use app\models\Admin;
use app\models\BaseModel;
use app\models\cases\CaseAssignment;
use app\models\cases\CaseAuditTrail;
use app\models\cases\CaseMessages;
use app\models\forms\CasesTypesForm;
use Exception;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class CaseController extends BaseController
{
    const DASHBOARD = 'dashboard';

    public function actionIndex($dateRange = null, $status = null)
    {
        $this->layout = self::DASHBOARD;
        $model = new CasesTypesForm();
        $data = $this->getRequest()->get();
        $query = CasesTypesForm::getCaseQuery($data);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
                'attributes' => ['created_at', 'status']
            ]
        ]);
        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'dateRange' => $dateRange,
            'status' => $status,
        ]);
    }

    public function actionActorListView()
    {
        $this->layout = self::DASHBOARD;
        $model = new CaseAssignment();
        $user = $this->getUser()->identity;
        $data = CaseAssignment::find()
            ->where(['assignee_id' => $user->id])
            ->andWhere(['!=', CaseAssignment::tableName() . '.status', Status::STATUS_WITHDRAW])
            ->joinwith('caseDetails');
        $dataProvider = new ActiveDataProvider([
            'query' => $data,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
                'attributes' => ['created_at', 'status']
            ]
        ]);
        return $this->render('actor-case-list-view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $caseId
     * @return Response
     * @handles updating Case status
     * @author medinat apanpa <medinatapampa@yahoo.com>
     */
    public function actionResolveCase($caseId)
    {
        $user = $this->getUser()->identity;
        $now = Utils::getCurrentDateTime();
        $caseHistory = new CaseAuditTrail([
            'case_id' => $this->getCaseId($caseId),
            'action_status' => 'Resolved Case',
            'actor' => $user->fullName,
            'role' => $user->role->label,
            'created_at' => $now,
        ]);
        $transaction = BaseModel::getDb()->beginTransaction();
        try {
            CaseAssignment::updateAll([
                'status' => Status::STATUS_RESOLVED,
                'updated_at' => $now,
            ], ['case_id' => $caseId]);
            $this->editModelData($caseId, CasesTypesForm::class, [
                'status' => Status::STATUS_RESOLVED,
                'updated_at' => $now,
            ]);
            $caseHistory->saveData();
            $transaction->commit();
            return $this->returnSuccess(Messages::getSuccessMessage('Case status', 'saved'));
        } catch (EntityNotSavedException $ex) {
            $transaction->rollBack();
            return $this->returnError(Messages::getFailureMessage('Case', 'saved'));
        }
    }

    /**
     * @return Response
     * @throws \yii\db\Exception
     * @author femi medunna <femimeduna@gmail.com>
     * @handles case assignee withdrawal
     */
    public function actionWithdrawCase()
    {
        $postData = $this->getRequest()->post();
        $caseId = ArrayHelper::getValue($postData, 'CasesTypesForm.id');
        $withdrawnCaseId = ArrayHelper::getValue($postData, 'CasesTypesForm.case_id');
        if (!$withdrawnCaseId) {
            return $this->returnError('Choose an assignee to withdraw');
        }
        $now = Utils::getCurrentDateTime();
        $user = $this->getUser()->identity;
        $caseAssignee = CaseAssignment::findOne(['id' => $withdrawnCaseId]);
        $assigneeName = ArrayHelper::getValue($caseAssignee, 'adminDetails.fullName');
        $transaction = BaseModel::getDb()->beginTransaction();
        try {
            $caseAssignee->status = Status::STATUS_WITHDRAW;
            $caseHistory = new CaseAuditTrail([
                'case_id' => $this->getCaseId($caseId),
                'action_status' => 'Withdrew Case from ' . $assigneeName,
                'actor' => $user->fullName,
                'role' => $user->role->label,
                'created_at' => $now,
            ]);
            $caseAssignee->saveData();
            $caseHistory->saveData();
            $transaction->commit();
            return $this->returnSuccess(Messages::getSuccessMessage('Case Assignee', 'withdrawn'));
        } catch (EntityNotSavedException $ex) {
            $transaction->rollBack();
            return $this->returnError(Messages::getFailureMessage('Case Assignee', 'withdrawn'));
        }
    }

    /**
     * @param $caseId
     * @throws Exception
     * @handles bulk actions
     * @author medinat apanpa<medinatapampa@yahoo.com>
     * @author femi meduna <femimeduna@gmail.com>
     */
    public function actionUpdateBulkStatus()
    {
        $postData = $this->getRequest()->post();
        $status = ArrayHelper::getValue($postData, 'status');
        $ids = ArrayHelper::getValue($postData, 'id');
        $user = $this->getUser()->identity;
        foreach ($ids as $caseId) {
            $caseBulkAuditTrail = new CaseAuditTrail([
                'case_id' => $caseId,
                'action_status' => $status . ' ' . 'Case',
                'actor' => $user->fullName,
                'role' => $user->role->label,
                'created_at' => Utils::getCurrentDateTime(),
            ]);
        }
        $transaction = BaseModel::getDb()->beginTransaction();
        try {
            CasesTypesForm::updateBulkCases($status, $ids);
            $caseBulkAuditTrail->save();
            $transaction->commit();
        } catch (EntityNotSavedException $ex) {
            $transaction->rollBack();
            return $this->returnError($ex->getMessage());
        }
        return $this->returnSuccess(Messages::getSuccessMessage('Cases', 'updated'));
    }

    /**
     * @param $caseId
     * @return string
     * @handles Case history audit trail
     * @author medinat apanpa <medinatapampa@yahoo.com>
     * @author femi meduna <femimeduna@gmail.com>
     */
    public function actionViewHistory($caseId)
    {
        $this->layout = self::DASHBOARD;
        $model = new CaseAuditTrail();
        $caseAssignmentData = CaseAssignment::findAll(['case_id' => $caseId]);
        $ids = ArrayHelper::getColumn($caseAssignmentData, 'id');
        $caseAudit = CaseAuditTrail::find()->where(['in', 'case_id', $ids]);
        $case = new ActiveDataProvider([
            'query' => $caseAudit,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_ASC],
                'attributes' => ['created_at', 'status']
            ]
        ]);

        return $this->render('view-history', ['model' => $model, 'case' => $case]);
    }

    /**
     * @param $caseId
     * @return string
     * @author meduna femi <femimeduna@gmail.com>
     * @handles the case view list for the AdminUser
     */
    public function actionViewCase($caseId)
    {
        $this->layout = self::DASHBOARD;
        $user = $this->getUser()->identity;
        $model = new CaseAssignment();
        $case = CasesTypesForm::findOne(['id' => $caseId]);
        $query = CaseAssignment::find()
            ->where([CaseAssignment::tableName() . '.case_id' => $case->id])
            ->joinWith(['adminDetails', 'caseDetails']);
        $cases = $query->all();
        $casesId = ArrayHelper::getColumn($cases, 'id');
        $adminName = $user->fullName;
        $caseText = CaseMessages::getMessages($casesId);
        return $this->render('/users/user/view-case', [
            'model' => $model,
            'case' => $query->one(),
            'admin' => $this->getSenderId(),
            'adminName' => $adminName,
            'caseText' => $caseText
        ]);
    }

    /**
     * @param $caseId
     * @return string
     * @handles View case and communicate with admins.
     * @author medinat apanpa <medinatapampa@yahoo.com>
     * @author femi meduna <femimeduna@gmail.com>
     */
    public function actionAdminCaseView($caseId)
    {
        $this->layout = self::DASHBOARD;
        $model = new CasesTypesForm();
        $user = $this->getUser()->identity;
        $case = CasesTypesForm::findOne($caseId);
        $caseAssignees = CaseAssignment::find()
            ->joinWith('adminDetails', 'caseDetails')
            ->where(['case_id' => $caseId])
            ->andWhere(['!=', CaseAssignment::tableName() . '.status', Status::STATUS_WITHDRAW])
            ->all();
        $assignedCaseId = ArrayHelper::getColumn($caseAssignees, 'id');
        $assignees = ArrayHelper::getColumn($caseAssignees, 'adminDetails.fullName');
        $assignee = implode(', ', $assignees);
        $adminName = $user->fullName;
        $caseText = CaseMessages::getMessages($assignedCaseId);

        return $this->render('admin-case-view', [
            'model' => $model,
            'case' => $case,
            'adminId' => $this->getSenderId(),
            'adminName' => $adminName,
            'assignee' => $assignee,
            'caseText' => $caseText
        ]);
    }

    /**
     * @return Response
     * @throws Exception
     * @author femi meduna <femimeduna@gmail.com>
     * @handles assigning a case to a user.
     */
    public function actionAssignCase()
    {
        $postData = $this->getRequest()->post();
        $user = $this->getUser()->identity;
        $assigneeId = ArrayHelper::getValue($postData, 'CaseAssignment.assignee_name');
        $admin = Admin::find()
            ->where(['admins.id' => $assigneeId])
            ->joinWith(['userCredential'])
            ->one();
        $assigneeEmail = ArrayHelper::getValue($admin, 'userCredential.email');
        $name = Arrayhelper::getValue($admin, 'first_name');
        $caseId = ArrayHelper::getValue($postData, 'CaseAssignment.id');
        $caseAssignId = ArrayHelper::getValue($postData, 'CaseAssignment.case_id');
        $message = ArrayHelper::getValue($postData, 'CaseAssignment.case_summary');
        $caseStatus = CasesTypesForm::findOne(['id' => $caseId]);
        $caseStatus->status = Status::STATUS_PENDING;
        $now = Utils::getCurrentDateTime();
        $case = new CaseAssignment([
            'case_id' => $caseId,
            'assignee_id' => $assigneeId,
            'created_at' => $now,
        ]);
        if (CaseAssignment::checkDuplicateAssignee($assigneeId, $caseId)) {
            return $this->returnError(Messages::ACTOR_EXIST);
        }
        $transaction = BaseModel::getDb()->beginTransaction();
        try {
            $case->saveData();
            $caseStatus->saveData();
            $caseHistory = new CaseAuditTrail([
                'case_id' => $case->id,
                'action_status' => 'Assigned Case to ' . $admin->fullName,
                'actor' => $user->fullName,
                'role' => $user->role->label,
                'created_at' => $now,
            ]);
            $caseHistory->saveData();
            $caseId = BaseModel::getDb()->getLastInsertID();
            CaseAssignment::assignCaseEmail($assigneeEmail, $name, $caseAssignId, $message);
            $transaction->commit();
            return $this->returnSuccess(Messages::CASE_ASSIGNED);
        } catch (EntityNotSavedException $ex) {
            $transaction->rollBack();
            return $this->returnError($ex->getMessage(), 'assign-case');
        }
    }

    public function actionNewCase($caseId)
    {
        $this->layout = self::DASHBOARD;
        $case = CasesTypesForm::findOne($caseId);
        $newCase = new CaseAssignment();
        return $this->render('/report/new-case', ['model' => $newCase, 'case' => $case]);
    }

    /***
     * @return Response
     * @throws Exception
     * @author medinat apanpa <medinatapampa@yahoo.com>
     * @author femi meduna <femimeduna@gmail.com>
     */
    public function actionAdminCaseReply()
    {
        $postData = $this->getRequest()->post();
        $message = ArrayHelper::getValue($postData, 'CasesTypesForm.reply_case');
        $replyCaseId = ArrayHelper::getValue($postData, 'CasesTypesForm.id');
        $case = CaseAssignment::findOne(['case_id' => $replyCaseId]);
        if ($case->case_id != $replyCaseId) {
            return $this->returnError(Messages::getNotFoundMessage('Case'));
        }
        $now = Utils::getCurrentDateTime();
        $user = $this->getUser()->identity;
        $caseMessage = new CaseMessages([
            'case_id' => $case->id,
            'sender_id' => $this->getSenderId(),
            'case_messages' => $message,
            'created_at' => $now
        ]);
        try {
            $caseHistory = new CaseAuditTrail([
                'case_id' => $case->id,
                'action_status' => 'Replied',
                'actor' => $user->fullName,
                'role' => $user->role->label,
                'created_at' => $now,
            ]);
            $caseMessage->saveData();
            $caseHistory->saveData();
            return $this->returnSuccess(Messages::getSuccessMessage('Case reply', 'sent'));
        } catch (EntityNotSavedException $ex) {
            return $this->returnError('case reply', 'view-case');
        }
    }

    /**
     * @handles Reply to admins
     * @author femi meduna <femimeduna@gmail.com>
     * @author medinat apanpa <medinatapampa@gmail.com>
     */
    public function actionCaseReply()
    {
        $postData = $this->getRequest()->post();
        $caseId = ArrayHelper::getValue($postData, 'CaseAssignment.id');
        $message = ArrayHelper::getValue($postData, 'CaseAssignment.reply_case');
        $now = Utils::getCurrentDateTime();
        $user = $this->getUser()->identity;
        $caseMessage = new CaseMessages([
            'case_id' => $caseId,
            'sender_id' => $this->getSenderId(),
            'case_messages' => $message,
            'created_at' => Utils::getCurrentDateTime()
        ]);
        try {
            $caseHistory = new CaseAuditTrail([
                'case_id' => $caseId,
                'action_status' => 'Replied',
                'actor' => $user->fullName,
                'role' => $user->role->label,
                'created_at' => $now,
            ]);
            $caseMessage->saveData();
            $caseHistory->saveData();
            return $this->returnSuccess(Messages::getSuccessMessage('Case reply', 'sent'));
        } catch (EntityNotSavedException $ex) {
            return $this->returnError('case reply', 'sent');
        }
    }

    public function actionCaseReminder($caseId)
    {
        $caseAssignments = CaseAssignment::find()->where(['case_id' => $caseId])->joinWith('adminDetails')->all();
        $viewedCaseId = CasesTypesForm::findOne(['id' => $caseId])->case_id;
        $user = $this->getUser()->identity;

        try {
            foreach ($caseAssignments as $caseAssignment) {
                $userEmail = $caseAssignment->adminDetails->userCredential->email;
                $userName = $caseAssignment->adminDetails->fullName;
                $caseReminderHistory = new CaseAuditTrail([
                    'case_id' => $caseAssignment->id,
                    'action_status' => 'Sent Reminder',
                    'actor' => $user->fullName,
                    'role' => $user->role->label,
                    'created_at' => Utils::getCurrentDateTime(),
                ]);
                $caseReminderHistory->saveData();
                CasesTypesForm::sendCaseReminderEmail($userEmail, $userName, $viewedCaseId);
            }
        } catch (EntityNotSavedException $ex) {
            return $this->returnError($ex->getMessage());
        }
        return $this->returnSuccess(Messages::getSuccessMessage('Case', 'reminder sent'));
    }
}
