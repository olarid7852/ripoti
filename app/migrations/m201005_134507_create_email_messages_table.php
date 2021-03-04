<?php

use yii\db\Migration;
use app\constants\MigrationConstants;

/**
 * Handles the creation of table `{{%email_messages}}`.
 */
class m201005_134507_create_email_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(MigrationConstants::TABLE_EMAIL_MESSAGES, [
            'id' => $this->primaryKey(),
            'sender_id' => $this->string()->notNull(),
            'recipient_id' => $this->string()->notNull(),
            'message' => $this->text()->notNull(),
            'timestamp' => $this->dateTime()
        ]);
        $this->dropColumn(MigrationConstants::TABLE_EMAIL_REPORTS, 'case_description', $this->text()->notNull());
        $this->dropColumn(MigrationConstants::TABLE_EMAIL_REPORTS, 'report_reply', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn(MigrationConstants::TABLE_EMAIL_REPORTS, 'report_reply', $this->string());
        $this->addColumn(MigrationConstants::TABLE_EMAIL_REPORTS, 'case_description', $this->text()->notNull());
        $this->dropTable(MigrationConstants::TABLE_EMAIL_MESSAGES);
    }
}
