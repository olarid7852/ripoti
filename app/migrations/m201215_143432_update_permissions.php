<?php

use app\constants\MigrationConstants;
use app\migrations\BaseMigration;
use CottaCush\Yii2\Date\DateUtils;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

/**
 * Class m201215_143432_update_permissions
 */
class m201215_143432_update_permissions extends BaseMigration
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
            $permissions = $this->setPermissions(
                array_diff(self::$permissions, $this->getPermissions())
            );
            $this->batchInsert(
                MigrationConstants::TABLE_PERMISSIONS,
                ['key', 'label', 'created_at', 'updated_at', 'status'],
                $permissions
            );

            $transaction->commit();
            // Grant all permissions to Admin
            $this->grantPermission(self::SYSTEM_ADMIN, self::$permissionsKeys);
        } catch (Exception $ex) {
            echo $ex->getMessage();
            $transaction->rollBack();
        }
    }

    public function down()
    {
        $this->delete(MigrationConstants::TABLE_ROLE_PERMISSIONS, []);
        $this->delete(MigrationConstants::TABLE_PERMISSIONS, []);
        $this->delete(MigrationConstants::TABLE_ROLES, []);

        $this->insert('permissions', [
            'key' => 'view-accepted-cases',
            'label' => 'View Accepted Cases',
            'status' => 'active',
            'created_at' => DateUtils::getMysqlNow(),
            'updated_at' => DateUtils::getMysqlNow()
        ]);
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
                self::VALUE_ACTIVE
            ];
        }
        return $arr;
    }

    private function getPermissions()
    {
        $query = new Query();
        $permissions = $query->select('label')
            ->from(MigrationConstants::TABLE_PERMISSIONS)->all();
        return ArrayHelper::getColumn($permissions, 'label');
    }
}