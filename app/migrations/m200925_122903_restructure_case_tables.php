<?php

use app\constants\MigrationConstants;
use yii\db\Migration;

/**
 * Class m200925_122903_restructure_case_tables
 */
class m200925_122903_restructure_case_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable(MigrationConstants::TABLE_CASE_HISTORY);
        $this->dropTable(MigrationConstants::TABLE_REPORT_CASES);

        $this->createTable(MigrationConstants::TABLE_REPORT_CASES, [
            'id' => $this->primaryKey(),
            'case_id' => $this->string()->notNull(),
            'status' => $this->string()->notNull()->defaultValue('unassigned'),
            'reporter_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->createTable(MigrationConstants::TABLE_ASSIGNED_CASES, [
            'id' => $this->primaryKey(),
            'case_id' => $this->integer()->notNull(),
            'assignee_id' => $this->integer()->notNull(),
            'status' => $this->string()->notNull()->defaultValue('new'),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->createTable(MigrationConstants::TABLE_CASE_MESSAGES, [
            'id' => $this->primaryKey(),
            'case_id' => $this->integer()->notNull(),
            'sender_id' => $this->integer()->notNull(),
            'case_messages' => $this->string()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
        ]);

        $this->createTable(MigrationConstants::TABLE_CASE_HISTORY, [
            'id' => $this->primaryKey(),
            'case_id' => $this->integer(),
            'action_status' => $this->string()->notNull(),
            'actor' => $this->string()->notNull(),
            'role' => $this->string()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_' . MigrationConstants::TABLE_REPORT_CASES . '_' . MigrationConstants::TABLE_REPORTS
            . '_reporter_id_id',
            MigrationConstants::TABLE_REPORT_CASES,
            'reporter_id',
            MigrationConstants::TABLE_REPORTS,
            'id'
        );
        $this->addForeignKey(
            'fk_' . MigrationConstants::TABLE_ASSIGNED_CASES . '_' . MigrationConstants::TABLE_ADMINS
            . '_assignee_id_id',
            MigrationConstants::TABLE_ASSIGNED_CASES,
            'assignee_id',
            MigrationConstants::TABLE_ADMINS,
            'id'
        );
        $this->addForeignKey(
            'fk_' . MigrationConstants::TABLE_ASSIGNED_CASES . '_' . MigrationConstants::TABLE_REPORT_CASES
            . '_case_id_id',
            MigrationConstants::TABLE_ASSIGNED_CASES,
            'case_id',
            MigrationConstants::TABLE_REPORT_CASES,
            'id'
        );
        $this->addForeignKey(
            'fk_' . MigrationConstants::TABLE_CASE_HISTORY . '_' . MigrationConstants::TABLE_ASSIGNED_CASES
            . '_case_id_id',
            MigrationConstants::TABLE_CASE_HISTORY,
            'case_id',
            MigrationConstants::TABLE_ASSIGNED_CASES,
            'id'
        );
        $this->addForeignKey(
            'fk_' . MigrationConstants::TABLE_CASE_MESSAGES . '_' . MigrationConstants::TABLE_ASSIGNED_CASES
            . '_case_id_id',
            MigrationConstants::TABLE_CASE_MESSAGES,
            'case_id',
            MigrationConstants::TABLE_ASSIGNED_CASES,
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(MigrationConstants::TABLE_CASE_HISTORY);
        $this->dropTable(MigrationConstants::TABLE_CASE_MESSAGES);
        $this->dropTable(MigrationConstants::TABLE_ASSIGNED_CASES);
        $this->dropTable(MigrationConstants::TABLE_REPORT_CASES);
    }
}
