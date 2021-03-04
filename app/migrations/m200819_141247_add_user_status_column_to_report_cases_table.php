<?php

use app\constants\MigrationConstants;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%report_cases}}`.
 */
class m200819_141247_add_user_status_column_to_report_cases_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn(
            MigrationConstants::TABLE_REPORT_CASES,
            'assigned_case_status',
            $this->string()->notNull()->defaultValue('new')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn(
            MigrationConstants::TABLE_REPORT_CASES,
            'assigned_case_status'
        );
    }
}
