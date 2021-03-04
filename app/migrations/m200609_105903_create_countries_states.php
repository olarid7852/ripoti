<?php

use yii\db\Migration;
use app\constants\MigrationConstants;

/**
 * Class m200609_105903_create_countries_states
 */
class m200609_105903_create_countries_states extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $install_sql = file_get_contents(dirname(__FILE__) . '/data/countries-states.sql');
        $this->execute($install_sql);
    }

    public function down()
    {
        $this->dropTable(MigrationConstants::TABLE_STATES);
        $this->dropTable(MigrationConstants::TABLE_COUNTRIES);
    }
}
