<?php

use app\constants\MigrationConstants;
use yii\db\Migration;

/**
 * Class m200828_132212_alter_case_id_column
 */
class m200828_132212_alter_case_id_column extends Migration
{

    public function up()
    {
        $this->alterColumn(
            MigrationConstants::TABLE_REPORT_CASES,
            'case_id',
            $this->string(20)
        );
    }

    public function down()
    {
        $this->alterColumn(
            MigrationConstants::TABLE_REPORT_CASES,
            'case_id',
            $this->string(20)->unique()
        );
    }
}
