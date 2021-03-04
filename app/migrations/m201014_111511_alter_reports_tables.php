<?php

use app\constants\MigrationConstants;
use yii\db\Migration;

/**
 * Class m201014_111511_alter_reports_tables
 */
class m201014_111511_alter_reports_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn(MigrationConstants::TABLE_REPORTS, 'violation_type_id', $this->integer()->unsigned());
        $this->dropForeignKey(
            'fk_' . MigrationConstants::TABLE_TWITTER_REPORTS . '_' . MigrationConstants::TABLE_VIOLATION_TYPES
            . '_violation_type_id_id',
            MigrationConstants::TABLE_TWITTER_REPORTS
        );
        $this->dropForeignKey(
            'fk_' . MigrationConstants::TABLE_REPORT_FORMS . '_' . MigrationConstants::TABLE_VIOLATION_TYPES
            . '_violation_type_id_id',
            MigrationConstants::TABLE_FORM_REPORTS
        );
        $this->dropForeignKey(
            'fk_' . MigrationConstants::TABLE_EMAIL_REPORTS . '_' . MigrationConstants::TABLE_VIOLATION_TYPES
            . '_violation_type_id_id',
            MigrationConstants::TABLE_EMAIL_REPORTS
        );

        $this->dropColumn(MigrationConstants::TABLE_TWITTER_REPORTS, 'violation_type_id');
        $this->dropColumn(MigrationConstants::TABLE_EMAIL_REPORTS, 'violation_type_id');
        $this->dropColumn(MigrationConstants::TABLE_FORM_REPORTS, 'violation_type_id');
        $this->addColumn(MigrationConstants::TABLE_TWITTER_REPORTS, 'country_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->addColumn(MigrationConstants::TABLE_EMAIL_REPORTS, 'violation_type_id', $this->integer()->unsigned());
        $this->addColumn(MigrationConstants::TABLE_FORM_REPORTS, 'violation_type_id', $this->integer()->unsigned());
        $this->addColumn(MigrationConstants::TABLE_TWITTER_REPORTS, 'violation_type_id', $this->integer()->unsigned());
        $this->dropColumn(MigrationConstants::TABLE_REPORTS, 'violation_type_id');
        $this->dropColumn(MigrationConstants::TABLE_TWITTER_REPORTS, 'country_id');
    }
}
