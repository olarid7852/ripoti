<?php

use yii\db\Migration;
use app\constants\MigrationConstants;

/**
 * Handles adding columns to table `{{%form_reports}}`.
 */
class m201012_210710_add_age_column_to_form_reports_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(MigrationConstants::TABLE_FORM_REPORTS, 'age', $this->string()->notNull());
        $this->addColumn(MigrationConstants::TABLE_EMAIL_REPORTS, 'country_id', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(MigrationConstants::TABLE_EMAIL_REPORTS, 'country_id');
        $this->dropColumn(MigrationConstants::TABLE_FORM_REPORTS, 'age');
    }
}
