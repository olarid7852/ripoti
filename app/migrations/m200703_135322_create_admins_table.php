<?php

use app\constants\MigrationConstants;
use CottaCush\Yii2\Date\DateUtils;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%admins}}`.
 */
class m200703_135322_create_admins_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $now = DateUtils::getMysqlNow();

        $this->createTable(MigrationConstants::TABLE_ADMINS, [
            'id' => $this->primaryKey(),
            'first_name' => $this->string()->notNull(),
            'last_name' => $this->string()->notNull(),
            'user_auth_id' => $this->integer()->notNull(),
            'status' => $this->string(100)->defaultValue('active')->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addForeignKey(
            'fk_' . MigrationConstants::TABLE_ADMINS . '_' . MigrationConstants::TABLE_USER_CREDENTIALS . '_user_auth_id_id',
            MigrationConstants::TABLE_ADMINS,
            'user_auth_id',
            MigrationConstants::TABLE_USER_CREDENTIALS,
            'id'
        );

        $this->insert(MigrationConstants::TABLE_ADMINS, [
            'first_name' => 'DRVP Portal',
            'last_name' => 'Admin',
            'user_auth_id' => 1,
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(MigrationConstants::TABLE_ADMINS);
    }
}
