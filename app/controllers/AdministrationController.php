<?php

namespace app\controllers;

use app\constants\Messages;
use app\constants\Status;
use app\exceptions\EntityNotSavedException;
use app\exceptions\EntityNotUpdatedException;
use app\libs\Utils;
use app\models\BaseModel;
use app\models\Permissions;
use app\models\Role;
use app\models\RolePermission;
use CottaCush\Yii2\Date\DateUtils;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;

class AdministrationController extends BaseController
{
    public function beforeAction($action)
    {
        $this->canAccess(Permissions::MANAGE_ADMIN);
        return parent::beforeAction($action);
    }
    /**
     * @return string
     * @author femi meduna <femimeduna@gmail.com>
     * @handles loading roles to view
     */
    public function actionIndex()
    {
        $this->layout = 'dashboard';
        $model = new Role();
        $query = Role::find()->where(['!=', Role::tableName() . '.status', Status::STATUS_DELETED]);
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
        return $this->render('administration-types', ['model' => $model, 'dataProvider' => $dataProvider]);
    }

    /**
     * @return string
     * @author femi meduna <femimeduna@gmail.com>
     * @handles creation of roles and permissions
     */
    public function actionCreateRoles()
    {
        $postData = $this->getRequest()->post();
        $now = DateUtils::getMysqlNow();
        $newRole = ArrayHelper::getValue($postData, 'Role.label');
        $status = ArrayHelper::getValue($postData, 'Role.status');
        $permissions = ArrayHelper::getValue($postData, 'Role.permissions');
        $key = BaseInflector::slug($newRole);
        $role = new Role([
            'label' => $newRole,
            'status' => $status,
            'key' => $key,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        if (Role::checkDuplicateRole($role)) {
            return $this->returnError(Messages::INVALID_ROLE_CREATED);
        }

        try {
            $role->saveData();
            RolePermission::saveRolePermissions($permissions, $role->id);
            return $this->returnSuccess(
                Messages::getSuccessMessage(
                    Messages::NEW_ROLE,
                    Messages::TASK_CREATE
                ),
                'index'
            );
        } catch (EntityNotSavedException $ex) {
            return $this->returnError(
                $ex->getMessage(),
                'index'
            );
        }
    }

    /**
     * @return string
     * @author femi meduna <femimeduna@gmail.com>
     * @handles updating role status
     */
    public function actionUpdateRoleStatus($roleId)
    {
        try {
            $this->updateModelStatus($roleId, Role::class, Messages::NEW_ROLE);
            return $this->returnSuccess(
                Messages::getSuccessMessage(
                    Messages::NEW_ROLE . ' status',
                    'updated'
                )
            );
        } catch (EntityNotUpdatedException $ex) {
            return $this->returnError($ex->getMessage());
        }
    }

    /**
     * @return string
     * @author femi meduna <femimeduna@gmail.com>
     * @handles updating role status
     */
    public function actionDeleteRole($roleId)
    {
        try {
            $this->editModelData($roleId, Role::class, [
                'updated_at' => Utils::getCurrentDateTime(),
            ]);
            return $this->returnSuccess(
                Messages::getSuccessMessage(
                    Messages::NEW_ROLE . 'successfully',
                    'deleted'
                )
            );
        } catch (EntityNotUpdatedException $ex) {
            return $this->returnError($ex->getMessage());
        }
    }

    /**
     * @return string
     * @author femi meduna <femimeduna@gmail.com>
     * @handles editing roles and permissions
     */
    public function actionEditRolePermission($roleId)
    {
        $role = $this->loadModelData(Role::class, $roleId);
        if (!$role) {
            return $this->returnError(Messages::getNotExistWarning('Role'));
        }
        $postData = $this->getRequest()->post();
        $permissions = ArrayHelper::getValue($postData, 'Role.permissions');
        $transaction = BaseModel::getDb()->beginTransaction();
        try {
            $role->saveData();
            RolePermission::deleteAll(['role_id' => $role->id]);
            RolePermission::saveRolePermissions($permissions, $role->id);
            $transaction->commit();
            return $this->returnSuccess(Messages::getSuccessMessage(Messages::NEW_ROLE, Messages::TASK_SAVE));
        } catch (EntityNotSavedException $ex) {
            $transaction->rollBack();
            return $this->returnError($ex->getMessage());
        }
    }
}
