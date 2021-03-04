<?php

namespace app\models;

use app\constants\Messages;
use app\constants\MigrationConstants;
use app\exceptions\EntityNotSavedException;
use CottaCush\Yii2\Date\DateUtils;
use yii\db\Exception;

/**
 * Class Permissions
 * femimeduna@gmail.com
 * @property mixed|null status
 * @property mixed|null names
 * @package app\models
 */
class RolePermission extends BaseModel
{
    public $permit_all;
    public $permissions;

    public static function tableName()
    {
        return MigrationConstants::TABLE_ROLE_PERMISSIONS;
    }

    /**
     * @param $permissions
     * @param $roleId
     * @throws EntityNotSavedException
     * @throws Exception
     * @author Malomo Damilare <damilaremalomo@gmail.com>
     */
    public static function saveRolePermissions($permissions, $roleId)
    {
        $rolePermissions = [];
        if (!empty($permissions)) {
            foreach ($permissions as $permission => $permission_id) {
                $rolePermissions[] = [$roleId, $permission_id, DateUtils::getMysqlNow()];
            }
            BaseModel::batchInsert(
                MigrationConstants::TABLE_ROLE_PERMISSIONS,
                ['role_id', 'permission_id', 'created_at'],
                $rolePermissions,
                Messages::NEW_ROLE
            );
        } else {
            throw new EntityNotSavedException('Role permissions not set');
        }
    }
}

