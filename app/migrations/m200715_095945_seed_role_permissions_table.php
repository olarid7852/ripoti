<?php

use app\constants\MigrationConstants;
use app\migrations\BaseMigration;
use CottaCush\Yii2\Date\DateUtils;
use yii\helpers\Inflector;

/**
 * femimeduna@gmail.com
 * Class m200715_095945_seed_role_permissions_table
 */
class m200715_095945_seed_role_permissions_table extends BaseMigration
{
    const SYSTEM_ADMIN = 'system-administrator';
    const VALUE_ACTIVE = 'active';
    public static $permissions = [
        'Access Invite', 'Manage Notification', 'Add Comments', 'Edit Cases', 'Manage Cases',
        'Manage Violation Type', 'Manage Update', 'Manage Users', 'Access Assigned Cases',
        'Access Menu', 'Access Report', 'Manage Role', 'Close Case',
    ];
    public static $permissionsKeys = [
        'access-invite', 'manage-notification', 'add-comments', 'edit-cases', 'manage-cases',
        'manage-violation-type', 'manage-update', 'manage-users', 'access-assigned-cases',
        'access-menu', 'access-report', 'manage-role', 'close-case',
    ];

    public function up()
    {
        $transaction = $this->db->beginTransaction();
        try {
            $this->addColumn(
                MigrationConstants::TABLE_ROLES,
                'created_by',
                $this->integer()
            );
            $this->addColumn(
                MigrationConstants::TABLE_ROLES,
                'updated_by',
                $this->integer()
            );
            $this->addColumn(
                MigrationConstants::TABLE_ADMINS,
                'role_key',
                $this->string(150)->after('last_name')
            );

            $this->addForeignKey(
                'fk_' . MigrationConstants::TABLE_ADMINS . '_' . MigrationConstants::TABLE_ROLES . '_role_key_key',
                MigrationConstants::TABLE_ADMINS,
                'role_key',
                MigrationConstants::TABLE_ROLES,
                'key'
            );
            $this->addForeignKey(
                'fk_' . MigrationConstants::TABLE_ROLES . '_' . MigrationConstants::TABLE_ADMINS . '_created_by_id',
                MigrationConstants::TABLE_ROLES,
                'created_by',
                MigrationConstants::TABLE_ADMINS,
                'id'
            );
            $this->addForeignKey(
                'fk_' . MigrationConstants::TABLE_ROLES . '_' . MigrationConstants::TABLE_ADMINS . '_updated_by_id',
                MigrationConstants::TABLE_ROLES,
                'updated_by',
                MigrationConstants::TABLE_ADMINS,
                'id'
            );
            // INSERT PERMISSION
            $permissions = $this->setPermissions(self::$permissions);
            $this->batchInsert(
                MigrationConstants::TABLE_PERMISSIONS,
                ['key', 'label', 'created_at', 'updated_at', 'status'],
                $permissions
            );
            // INSERT ADMIN ROLE
            $now = DateUtils::getMysqlNow();
            $this->batchInsert(
                MigrationConstants::TABLE_ROLES,
                ['key', 'label', 'created_at', 'updated_at', 'status'],
                [
                    [self::SYSTEM_ADMIN, 'System Administrator', $now, $now, self::VALUE_ACTIVE, ]
                ]
            );
            // GRANT ADMIN ROLE ALL PERMISSIONS
            $this->grantPermission(self::SYSTEM_ADMIN, self::$permissionsKeys);
        } catch (Exception $ex) {
            echo $ex->getMessage();
            $transaction->rollBack();
        }
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk_' . MigrationConstants::TABLE_ADMINS . '_' . MigrationConstants::TABLE_ROLES . '_role_key_key',
            MigrationConstants::TABLE_ADMINS
        );
        $this->dropForeignKey(
            'fk_' . MigrationConstants::TABLE_ROLES . '_' . MigrationConstants::TABLE_ADMINS . '_created_by_id',
            MigrationConstants::TABLE_ROLES
        );
        $this->dropForeignKey(
            'fk_' . MigrationConstants::TABLE_ROLES . '_' . MigrationConstants::TABLE_ADMINS . '_updated_by_id',
            MigrationConstants::TABLE_ROLES
        );
        $this->dropColumn(MigrationConstants::TABLE_ROLES, 'created_by');
        $this->dropColumn(MigrationConstants::TABLE_ROLES, 'updated_by');
        $this->dropColumn(MigrationConstants::TABLE_ADMINS, 'role_key');
        $this->delete(MigrationConstants::TABLE_ROLE_PERMISSIONS, []);
        $this->delete(MigrationConstants::TABLE_PERMISSIONS, []);
        $this->delete(MigrationConstants::TABLE_ROLES, []);
    }

    private function setPermissions($permissions)
    {
        $arr = [];
        foreach ($permissions as $index => $permission) {
            $arr[] = [
                Inflector::slug($permission),
                $permission,
                DateUtils::getMysqlNow(),
                DateUtils::getMysqlNow(),
                'active'
            ];
        }
        return $arr;
    }
}
