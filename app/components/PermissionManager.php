<?php

namespace app\components;

use app\models\Permissions;
use app\models\Role;
use app\models\Status;
use cottacush\rbac\BasePermissionManager;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class PermissionManager
 * @package app\components
 * @author Malomo Damilare <damilare@cottacush.com>
 */
class PermissionManager extends BasePermissionManager
{
    /**
     * @return array|ActiveRecord[]
     * @author Olawale Lawal <wale@cottacush.com>
     */
    public function getRoles()
    {
        return Role::find()->asArray()->all();
    }

    /**
     * Gets a role using the role key
     * @param $key
     * @return array|null|ActiveRecord
     * @author Olawale Lawal <wale@cottacush.com>
     */
    public function getRole($key)
    {
        $cache = Yii::$app->cache;
        $role = $cache->get($key);
        if (!$role) {
            $role = Role::find()->where(['key' => $key, 'status' => Status::STATUS_ACTIVE])->limit(1)->one();
            $cache->set($key, $role, Yii::$app->params['defaultCacheTimeout']);
        }
        return $role;
    }

    /**
     * @param $roleId
     * @return array|null|ActiveRecord
     * @author Olawale Lawal <wale@cottacush.com>
     */
    public function getRoleById($roleId)
    {
        return Role::find()->where(['id' => $roleId, 'status' => Status::STATUS_ACTIVE])->limit(1)->one();
    }

    /**
     * @return array|ActiveRecord[]
     * @author Olawale Lawal <wale@cottacush.com>
     */
    public function getPermissions()
    {
        return Permissions::find()->where(['status' => Status::STATUS_ACTIVE])->asArray()->all();
    }

    /**
     * @param $key
     * @return array|ActiveRecord[]
     * @author Maryfaith Mgbede <adaamgbede@gmail.com>
     */
    public function getPermission($key)
    {
        return Permissions::find()
            ->where(['key' => $key])->andWhere(['status' => Status::STATUS_ACTIVE])->limit(1)->one();
    }

    /**
     * @param $permissionId
     * @return array|null|ActiveRecord
     * @author Olawale Lawal <wale@cottacush.com>
     */
    public function getPermissionById($permissionId)
    {
        return Permissions::find()->where(['id' => $permissionId, 'status' => Status::STATUS_ACTIVE])->limit(1)->one();
    }

    /**
     * @return array|ActiveRecord
     * @throws \Throwable
     * @author Maryfaith Mgbede <adaamgbede@gmail.com>
     */
    public function getUserRole()
    {
        $user = Yii::$app->getModule('admin')->get('user')->identity;
        $role = ArrayHelper::getValue($user, 'role');
        return $this->getRole($role);
    }

    /**
     * @return array|ActiveRecord[]
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @author Olawale Lawal <wale@cottacush.com>
     */
    public function getUserPermissions()
    {
        /** @var Role $userRole */
        $userRole = $this->getUserRole();
        return $userRole ? $userRole->getCachedPermissions() : [];
    }

    /**
     * @param $permissionKey
     * @return bool
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @author Olawale Lawal <wale@cottacush.com>
     */
    public function canAccess($permissionKey)
    {
        $permissions = array_column($this->getUserPermissions(), 'key');
        return in_array($permissionKey, $permissions);
    }

}