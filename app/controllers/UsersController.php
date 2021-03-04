<?php

namespace app\controllers;

use app\constants\Messages;
use app\constants\Status;
use app\exceptions\EntityNotSavedException;
use app\exceptions\EntityNotUpdatedException;
use app\exceptions\InviteTokenValidationException;
use app\libs\Utils;
use app\models\Admin;
use app\models\BaseModel;
use app\models\cases\CaseAssignment;
use app\models\cases\CaseAuditTrail;
use app\models\forms\CasesTypesForm;
use app\models\forms\InviteTypesForm;
use app\models\forms\SignUpForm;
use app\models\Permissions;
use app\models\UserCredential;
use cottacush\userauth\exceptions\UserCreationException;
use yii\data\ActiveDataProvider;
use app\models\forms\UsersTypesForm;
use yii\helpers\ArrayHelper;

//ADMINISTRATION TYPES
class UsersController extends BaseController
{
    public function beforeAction($action)
    {
        $this->canAccess(Permissions::MANAGE_USERS);
        return parent::beforeAction($action);
    }
    /**
     * @return string
     * @author femi meduna <femimeduna@gmail.com>
     * @handles loading users to view
     */
    public function actionIndex()
    {
        $this->layout = 'dashboard';
        $model = new Admin();
        $query = Admin::find()->joinWith(['userEmail', 'role']);
        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 20],
                'sort' => [
                    'defaultOrder' => ['created_at' => SORT_DESC],
                    'attributes' => ['created_at', 'status']
                ],
            ]
        );
        return $this->render('admin/index', ['model' => $model, 'dataProvider' => $dataProvider]);
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\db\Exception
     * @author femi meduna <femimeduna@gmail.com>
     * @handles user sign up
     */
    public function actionSignUpUser()
    {
        $postData = $this->getRequest()->post();
        $token = ArrayHelper::getValue($postData, 'SignUpForm.token');
        $transaction = BaseModel::getDb()->beginTransaction();
        try {
            $invite = InviteTypesForm::validateToken($token);
            $register = new SignUpForm();
            $register->load($postData);
            $register->createAdminUser();
            $invite->acceptInvite();
            $transaction->commit();
            return $this->returnSuccess(
                Messages::getSuccessMessage('Profile', 'Created') . 'Please log in',
                '/admin/login'
            );
        } catch (InviteTokenValidationException $ex) {
            $transaction->rollBack();
            return $this->returnError($ex->getMessage());
        } catch (UserCreationException $ex) {
            $transaction->rollBack();
            return $this->returnError($ex->getMessage());
        } catch (EntityNotSavedException $ex) {
            $transaction->rollBack();
            return $this->returnError($ex->getMessage());
        }
    }

    /**
     * @param $userId
     * @return \yii\web\Response
     * @author femi meduna <femimeduna@gmail.com>
     */
    public function actionUpdateAdminUserStatus($userId)
    {
        try {
            $this->updateModelStatus($userId, Admin::class, 'User');
            return $this->returnSuccess(
                Messages::getSuccessMessage(
                    'User status',
                    'changed'
                )
            );
        } catch (EntityNotUpdatedException $ex) {
            return $this->returnError($ex->getMessage());
        }
    }

    /**
     * @param $userId
     * @return \yii\web\Response
     * @throws \Exception
     * @author femi meduna <femimeduna@gmail.com>
     * @handles editing violations
     */
    public function actionEditUser($userId, $authId)
    {
        try {
            $this->editModelData($authId, UserCredential::class, [
                'updated_at' => Utils::getCurrentDateTime(),
            ]);
            $this->editModelData($userId, UsersTypesForm::class, [
                'updated_at' => Utils::getCurrentDateTime(),
            ]);
        } catch (EntityNotSavedException $ex) {
            return $this->returnError($ex->getMessage());
        }
        return $this->returnSuccess(Messages::getSuccessMessage('User', 'updated'));
    }

    /**
     * @param $caseId
     * @return \yii\web\Response
     * @handles update of assignee case status(accept)
     */
    public function actionAcceptCase($caseId)
    {
        $user = $this->getUser()->identity;
        $assignedCase = CaseAssignment::findOne(['id' => $caseId]);
        $assignedCaseId = $assignedCase->case_id;
        $acceptCase = new CaseAuditTrail([
            'case_id' => $caseId,
            'action_status' => 'Accepted Case',
            'actor' => $user->fullName,
            'role' => $user->role->label,
            'created_at' => Utils::getCurrentDateTime(),
        ]);
        $case = CasesTypesForm::find()->joinWith('caseDetails')->where(['report_cases.id' => $assignedCaseId])->one();
        $case->status = Status::STATUS_PROGRESS;
        $transaction = BaseModel::getDb()->beginTransaction();
        try {
            $this->editModelData($caseId, CaseAssignment::class, [
                'status' => Status::STATUS_PROGRESS,
                'updated_at' => Utils::getCurrentDateTime(),
            ]);
            $case->saveData();
            $acceptCase->saveData();
            $transaction->commit();
            return $this->returnSuccess(Messages::getSuccessMessage('Case status', 'changed'));
        } catch (EntityNotSavedException $ex) {
            $transaction->rollBack();
            return $this->returnError(Messages::getFailureMessage('Case', 'updated'));
        }
    }

    public function actionDeclineCaseStatus($caseId)
    {
        $user = $this->getUser()->identity;
        $caseHistory = new CaseAuditTrail([
            'case_id' => $caseId,
            'action_status' => 'Declined Case',
            'actor' => $user->fullName,
            'role' => $user->role->label,
            'created_at' => Utils::getCurrentDateTime(),
        ]);
        $transaction = BaseModel::getDb()->beginTransaction();
        try {
            $caseHistory->saveData();
            $this->editModelData($caseId, CaseAssignment::class, [
                'status' => Status::STATUS_DECLINED,
                'updated_at' => Utils::getCurrentDateTime(),
            ]);
            $transaction->commit();
            return $this->returnSuccess(
                Messages::getSuccessMessage('Case status', 'changed')
            );
        } catch (EntityNotSavedException $ex) {
            $transaction->rollBack();
            return $this->returnError($ex->getMessage());
        }
    }

    /**
     * @param $caseId
     * @return \yii\web\Response
     */
    public function actionUpdateUserCaseStatus($caseId)
    {
        try {
            $this->updateModelStatus($caseId, CasesTypesForm::class, 'Case');
            return $this->returnSuccess(
                Messages::getSuccessMessage(
                    'User status',
                    'changed'
                )
            );
        } catch (EntityNotUpdatedException $ex) {
            return $this->returnError($ex->getMessage());
        }
    }
}
