<?php

use app\constants\MigrationConstants;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%report_form}}`.
 */
class m200831_135142_add_consent_column_to_report_form_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn(
            MigrationConstants::TABLE_REPORT_FORMS,
            'data_consent',
            $this->smallInteger()->notNull()->defaultValue('0')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn(
            MigrationConstants::TABLE_REPORT_FORMS,
            'data_consent'
        );
    }
}
