<?php

use yii\db\Migration;
use app\constants\MigrationConstants;
use yii\db\Query;

/**
 * Class m201215_143425_update_permissions_fk
 */
class m201215_143425_update_permissions_fk extends Migration
{
    public function up()
    {
        try {
            $roleKeyKeyConstraintExist = $this->foreignKeyExist(MigrationConstants::TABLE_ADMINS, MigrationConstants::TABLE_ROLES,
                'role_key', 'key', getenv('DB_NAME'));
            $createdByIdConstraintExist = $this->foreignKeyExist(MigrationConstants::TABLE_ROLES, MigrationConstants::TABLE_ADMINS,
                'created_by', 'id', getenv('DB_NAME'));
            $updatedByIdConstraintExist = $this->foreignKeyExist(MigrationConstants::TABLE_ROLES, MigrationConstants::TABLE_ADMINS,
                'updated_by', 'id', getenv('DB_NAME'));

            if (!$roleKeyKeyConstraintExist){
                $this->addForeignKey(
                    'fk_' . MigrationConstants::TABLE_ADMINS . '_' . MigrationConstants::TABLE_ROLES . '_role_key_key',
                    MigrationConstants::TABLE_ADMINS,
                    'role_key',
                    MigrationConstants::TABLE_ROLES,
                    'key'
                );
            }

            if (!$createdByIdConstraintExist) {
                $this->addForeignKey(
                    'fk_' . MigrationConstants::TABLE_ROLES . '_' . MigrationConstants::TABLE_ADMINS . '_created_by_id',
                    MigrationConstants::TABLE_ROLES,
                    'created_by',
                    MigrationConstants::TABLE_ADMINS,
                    'id'
                );
            }

            if (!$updatedByIdConstraintExist) {
                $this->addForeignKey(
                    'fk_' . MigrationConstants::TABLE_ROLES . '_' . MigrationConstants::TABLE_ADMINS . '_updated_by_id',
                    MigrationConstants::TABLE_ROLES,
                    'updated_by',
                    MigrationConstants::TABLE_ADMINS,
                    'id'
                );
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
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
    }

    private function foreignKeyExist($tableName, $referencedTableName, $columnName, $referencedColumnName, $dbName) {
        $query = new Query();
        $query->select(['TABLE_NAME'])
            ->from('INFORMATION_SCHEMA.KEY_COLUMN_USAGE')
            ->where(['TABLE_NAME' => $tableName,
                'REFERENCED_TABLE_NAME' => $referencedTableName,
                'COLUMN_NAME' => $columnName,
                'REFERENCED_COLUMN_NAME' => $referencedColumnName,
                'TABLE_SCHEMA' => $dbName]);
        return $query->count() > 0 ? true : false;
    }
}

