<?php

use yii\db\Migration;
use app\constants\MigrationConstants;

/**
 * Class m200615_102103_create_table_report_form
 */
class m200615_102103_create_table_report_form extends Migration
{
    /**
     * {@inheritdoc}
     */
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable(MigrationConstants::TABLE_VIOLATION_TYPES, [
            'id' => $this->primaryKey()->unsigned(),
            'status' =>$this->string()->notNull()->defaultValue('active'),
            'names' => $this->string(45)->notNull(),
            'key' => $this->string()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->createTable(MigrationConstants::TABLE_REPORT_FORMS, [
            'id' => $this->primaryKey()->unsigned(),
            'reporting_as' => $this->string(45)->notNull(),
            'violation_type_id' => $this->integer()->unsigned()->notNull(),
            'occurred_when' => $this->string()->notNull(),
            'case_subject' => $this->string(255)->notNull(),
            'case_description' => $this->string(255)->notNull(),
            'country_id' => $this->integer(11)->notNull(),
            'state_id' => $this->integer(11)->notNull(),
            'gender' => $this->string()->notNull(),
            'first_name' => $this->string()->notNull(),
            'last_name' => $this->string()->notNull(),
            'phone_number' => $this->string(),
            'email' => $this->string(),
            'other_means_of_contact' => $this->string(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addForeignKey(
            'fk_report_form_countries_country_id_id',
            MigrationConstants::TABLE_REPORT_FORMS,
            'country_id',
            MigrationConstants::TABLE_COUNTRIES,
            'id'
        );

        $this->addForeignKey(
            'fk_report_form_states_state_id_id',
            MigrationConstants::TABLE_REPORT_FORMS,
            'state_id',
            MigrationConstants::TABLE_STATES,
            'id'
        );

        $this->addForeignKey(
            'fk_'. MigrationConstants::TABLE_REPORT_FORMS .'_'. MigrationConstants::TABLE_VIOLATION_TYPES
            .'_violation_type_id_id',
            MigrationConstants::TABLE_REPORT_FORMS,
            'violation_type_id',
            MigrationConstants::TABLE_VIOLATION_TYPES,
            'id'
        );
    }

    public function down()
    {
        $this->dropTable(MigrationConstants::TABLE_REPORT_FORMS);
        $this->dropTable(MigrationConstants::TABLE_VIOLATION_TYPES);
    }
}
