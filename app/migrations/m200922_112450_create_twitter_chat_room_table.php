<?php

use app\constants\MigrationConstants;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%twitter_chat_room}}`.
 */
class m200922_112450_create_twitter_chat_room_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn(MigrationConstants::TABLE_TWITTER_REPORTS, 'case_description');
        $this->dropColumn(MigrationConstants::TABLE_TWITTER_REPORTS, 'report_reply');
        $this->createTable(MigrationConstants::TABLE_TWITTER_CHAT_ROOM, [
            'id' => $this->primaryKey(),
            'sender_id' => $this->string()->notNull(),
            'recipient_id' => $this->string()->notNull(),
            'message' => $this->string()->notNull(),
            'timestamp' => $this->dateTime()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn(MigrationConstants::TABLE_TWITTER_REPORTS, 'case_description', $this->string()->notNull());
        $this->addColumn(MigrationConstants::TABLE_TWITTER_REPORTS, 'report_reply', $this->string()->notNull());
        $this->dropTable(MigrationConstants::TABLE_TWITTER_CHAT_ROOM);
    }
}
