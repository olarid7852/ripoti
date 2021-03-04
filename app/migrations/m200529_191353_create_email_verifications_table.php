<?php

use app\constants\MigrationConstants;
use yii\db\Migration;

/**
 * Class m200529_191353_create_email_verifications_table
 */
class m200529_191353_create_email_verifications_table extends Migration
{
    /**
     * {@inheritdoc}
     */

    public function up()
    {
        $this->createTable(MigrationConstants::TABLE_EMAIL_VERIFICATIONS, [
            'id' => $this->primaryKey()->unsigned(),
            'user_auth_id' => $this->integer(11)->notNull(),
            'token' => $this->string(50)->notNull(),
            'created_at' => $this->dateTime()->notNull()
        ]);

        $this->addForeignKey(
            'fk_email_verifications_user_auth_id_id',
            MigrationConstants::TABLE_EMAIL_VERIFICATIONS,
            'user_auth_id',
            MigrationConstants::TABLE_USER_CREDENTIALS,
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable(MigrationConstants::TABLE_EMAIL_VERIFICATIONS);
    }
}
