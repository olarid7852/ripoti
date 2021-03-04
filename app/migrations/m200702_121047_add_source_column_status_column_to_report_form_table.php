<?php

use app\constants\MigrationConstants;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%report_form}}`.
 */
class m200702_121047_add_source_column_status_column_to_report_form_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn(
            MigrationConstants::TABLE_REPORT_FORMS,
            'status',
            $this->string()
                ->notNull()
                ->defaultValue('unverified')
        );

        $this->addColumn(
            MigrationConstants::TABLE_REPORT_FORMS,
            'source',
            $this->string()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn(
            MigrationConstants::TABLE_REPORT_FORMS,
            'status'
        );
        $this->dropColumn(
            MigrationConstants::TABLE_REPORT_FORMS,
            'source'
        );
    }
}
