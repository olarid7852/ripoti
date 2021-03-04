<?php

use app\constants\MigrationConstants;
use yii\db\Migration;

/**
 * @author femi meduna <femimeduna@gmail.com>
 * Handles the creation of table `{{%invites}}`.
 */
class m200717_130856_create_invites_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable(MigrationConstants::TABLE_INVITES, [
            'id' => $this->primaryKey(),
            'email' =>$this->string()->notNull(),
            'role_key' => $this->string(150),
            'token' => $this->string()->notNull(),
            'status' =>$this->string()->notNull()->defaultValue('pending'),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addForeignKey(
            'fk_' . MigrationConstants::TABLE_INVITES . '_' . MigrationConstants::TABLE_ROLES . '_role_key_key',
            MigrationConstants::TABLE_INVITES,
            'role_key',
            MigrationConstants::TABLE_ROLES,
            'key'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropForeignKey(
            'fk_' . MigrationConstants::TABLE_INVITES . '_' . MigrationConstants::TABLE_ROLES . '_role_key_key',
            MigrationConstants::TABLE_INVITES
        );

        $this->dropTable(MigrationConstants::TABLE_INVITES);
    }
}
