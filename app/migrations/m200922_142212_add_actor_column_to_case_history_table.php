<?php

use app\constants\MigrationConstants;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%case_history}}`.
 */
class m200922_142212_add_actor_column_to_case_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
     public function up()
    {
        $this->addColumn(
            MigrationConstants::TABLE_CASE_HISTORY,
            'actor',
            $this->string()->notNull()
        );
    }

        /**
         * {@inheritdoc}
         */
        public function down()
    {
        $this->dropColumn(
            MigrationConstants::TABLE_CASE_HISTORY,
            'actor'
        );
    }
}
