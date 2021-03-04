<?php

use app\constants\MigrationConstants;
use yii\db\Migration;

/**
 * Class m200915_181803_reports_table
 */
class m200915_181803_reports_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey(
            'fk_' . MigrationConstants::TABLE_REPORT_CASES . '_' . MigrationConstants::TABLE_REPORT_FORMS . '_reporter_id_id',
            MigrationConstants::TABLE_REPORT_CASES
        );
        $this->dropColumn(MigrationConstants::TABLE_REPORT_CASES, 'reporter_id');
        $this->addColumn(
            MigrationConstants::TABLE_REPORT_CASES,
            'report_id',
            $this->integer()
        );

        $this->createTable(MigrationConstants::TABLE_REPORTS, [
            'id' => $this->primaryKey(),
            'form_report_id' => $this->integer()->unsigned(),
            'twitter_report_id' => $this->integer(),
            'email_report_id' => $this->integer(),
            'status' => $this->string()->notNull()->defaultValue('unverified'),
            'source' => $this->string()->notNull(),
            'data_consent' => $this->smallInteger()->notNull()->defaultValue('0'),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()
        ]);
        $this->createTable(MigrationConstants::TABLE_TWITTER_REPORTS, [
            'id' => $this->primaryKey(),
            'sender_id' => $this->string()->notNull(),
            'twitter_handle' => $this->string()->notNull(),
            'violation_type_id' => $this->integer()->unsigned(),
            'name' => $this->string()->notNull(),
            'case_description' => $this->string()->notNull(),
            'report_reply' => $this->string(),
            'location' => $this->string()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()
        ]);
        $this->createTable(MigrationConstants::TABLE_EMAIL_REPORTS, [
            'id' => $this->primaryKey(),
            'violation_type_id' => $this->integer()->unsigned(),
            'reporter_email' => $this->string()->notNull(),
            'case_description' => $this->string()->notNull(),
            'report_reply' => $this->string(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()
        ]);
        $this->createTable(MigrationConstants::TABLE_CASE_HISTORY, [
            'id' => $this->primaryKey(),
            'case_id' => $this->integer()->unsigned(),
            'action_status' => $this->string()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
        ]);

        $this->dropColumn(MigrationConstants::TABLE_REPORT_FORMS, 'source');
        $this->dropColumn(MigrationConstants::TABLE_REPORT_FORMS, 'status');
        $this->dropColumn(MigrationConstants::TABLE_REPORT_FORMS, 'data_consent');
        $this->renameTable(MigrationConstants::TABLE_REPORT_FORMS, MigrationConstants::TABLE_FORM_REPORTS);

        $this->addForeignKey(
            'fk_' . MigrationConstants::TABLE_REPORT_CASES . '_' . MigrationConstants::TABLE_REPORTS
            . '_report_id_id',
            MigrationConstants::TABLE_REPORT_CASES,
            'report_id',
            MigrationConstants::TABLE_REPORTS,
            'id'
        );
        $this->addForeignKey(
            'fk_' . MigrationConstants::TABLE_CASE_HISTORY . '_' . MigrationConstants::TABLE_REPORT_CASES
            . '_case_id_id',
            MigrationConstants::TABLE_CASE_HISTORY,
            'case_id',
            MigrationConstants::TABLE_REPORT_CASES,
            'id'
        );
        $this->addForeignKey(
            'fk_' . MigrationConstants::TABLE_REPORTS . '_' . MigrationConstants::TABLE_FORM_REPORTS
            . '_form_report_id_id',
            MigrationConstants::TABLE_REPORTS,
            'form_report_id',
            MigrationConstants::TABLE_FORM_REPORTS,
            'id'
        );
        $this->addForeignKey(
            'fk_' . MigrationConstants::TABLE_REPORTS . '_' . MigrationConstants::TABLE_TWITTER_REPORTS
            . '_twitter_report_id_id',
            MigrationConstants::TABLE_REPORTS,
            'twitter_report_id',
            MigrationConstants::TABLE_TWITTER_REPORTS,
            'id'
        );
        $this->addForeignKey(
            'fk_' . MigrationConstants::TABLE_REPORTS . '_' . MigrationConstants::TABLE_EMAIL_REPORTS
            . '_email_report_id_id',
            MigrationConstants::TABLE_REPORTS,
            'email_report_id',
            MigrationConstants::TABLE_EMAIL_REPORTS,
            'id'
        );
        $this->addForeignKey(
            'fk_' . MigrationConstants::TABLE_TWITTER_REPORTS . '_' . MigrationConstants::TABLE_VIOLATION_TYPES
            . '_violation_type_id_id',
            MigrationConstants::TABLE_TWITTER_REPORTS,
            'violation_type_id',
            MigrationConstants::TABLE_VIOLATION_TYPES,
            'id'
        );
        $this->addForeignKey(
            'fk_' . MigrationConstants::TABLE_EMAIL_REPORTS . '_' . MigrationConstants::TABLE_VIOLATION_TYPES
            . '_violation_type_id_id',
            MigrationConstants::TABLE_EMAIL_REPORTS,
            'violation_type_id',
            MigrationConstants::TABLE_VIOLATION_TYPES,
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(MigrationConstants::TABLE_CASE_HISTORY);
        $this->dropTable(MigrationConstants::TABLE_REPORTS);
        $this->dropTable(MigrationConstants::TABLE_TWITTER_REPORTS);
        $this->dropTable(MigrationConstants::TABLE_EMAIL_REPORTS);
    }
}
