<?php

namespace app\migrations;

use app\constants\MigrationConstants;
use CottaCush\Yii2\Date\DateUtils;
use Yii;
use yii\caching\TagDependency;
use yii\db\Migration;
use yii\db\Query;

class BaseMigration extends Migration
{
    const PERMISSIONS = 'permissions';

    /**
     * @author Olawale Lawal <wale@cottacush.com>
     * @param $role
     * @param array|string $permissions
     */
    public function grantPermission($role, $permissions)
    {
        $query = new Query();
        $roleId = $query->select('id')
            ->from(MigrationConstants::TABLE_ROLES)
            ->where(['key' => $role])->scalar();
        $permissionId = $query->select('id')
            ->from(MigrationConstants::TABLE_PERMISSIONS)
            ->where(['key' => $permissions])->column();
        $existingPermissions = $query->select('permission_id')
            ->from(MigrationConstants::TABLE_ROLE_PERMISSIONS)
            ->where(['role_id' => $roleId, 'permission_id' => $permissionId])->column();
        $changes = array_diff($permissionId, $existingPermissions);
        if (!$changes) {
            return;
        }
        $data = [];
        foreach ($changes as $permissionId) {
            $data[] = [
                'permission_id' => $permissionId,
                'role_id' => $roleId,
                'created_at' => DateUtils::getMysqlNow()
            ];
        }
        $this->batchInsert(
            MigrationConstants::TABLE_ROLE_PERMISSIONS,
            ['permission_id', 'role_id', 'created_at'],
            $data
        );
        TagDependency::invalidate(Yii::$app->cache, [self::PERMISSIONS]);
    }
}