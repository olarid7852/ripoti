<?php

use app\constants\MigrationConstants;
use yii\db\Migration;

/**
 * femimeduna@gmail.com
 * Handles adding columns to table `{{%report_form}}`.
 */
class m200709_061333_add_report_reply_column_to_report_form_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn(
            MigrationConstants::TABLE_REPORT_FORMS,
            'report_reply',
            $this->text()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn(
            MigrationConstants::TABLE_REPORT_FORMS,
            'report_reply'
        );
    }
}
