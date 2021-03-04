<?php

use app\constants\MigrationConstants;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%report_cases}}`.
 */
class m200804_094912_create_report_cases_table extends Migration
{
    /**
     * @return bool|void|null
     */
    public function up()
    {
        $this->createTable(MigrationConstants::TABLE_REPORT_CASES, [
            'id' => $this->primaryKey()->unsigned(),
            'case_id' => $this->string(20)->unique(),
            'assignee_id' => $this->integer()->notNull(),
            'status' => $this->string(20)->notNull()->defaultValue('pending'),
            'reporter_id' => $this->integer()->unsigned()->notNull(),
            'case_summary' => $this->string(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addForeignKey(
            'fk_' . MigrationConstants::TABLE_REPORT_CASES . '_' . MigrationConstants::TABLE_ADMINS
            . '_assignee_id_id',
            MigrationConstants::TABLE_REPORT_CASES,
            'assignee_id',
            MigrationConstants::TABLE_ADMINS,
            'id'
        );

        $this->addForeignKey(
            'fk_' . MigrationConstants::TABLE_REPORT_CASES . '_' . MigrationConstants::TABLE_REPORT_FORMS
            . '_reporter_id_id',
            MigrationConstants::TABLE_REPORT_CASES,
            'reporter_id',
            MigrationConstants::TABLE_REPORT_FORMS,
            'id'
        );
    }

    public function down()
    {
        $this->dropTable(MigrationConstants::TABLE_REPORT_CASES);
    }
}