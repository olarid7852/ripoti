<?php

use yii\db\Migration;
use cottacush\userauth\libs\Utils;

/**
 * Class m200522_065901_create_table_user_credentials
 */
class m200522_065901_create_table_user_credentials extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up() 
    {
        $now = Utils::getCurrentDateTime();

        $this->batchInsert(
            'user_types',
            ['id', 'name', 'created_at', 'updated_at'],
            [
                [1, 'admin', $now, $now],
                [2, 'reporter', $now, $now]
            ]
        );

        $this->insert('user_credentials', [
            'email' => 'digitalrights@putsbox.com',
            'password' => Utils::encryptPassword('12345'),
            'user_type_id' => 1,
            'status' => 'active',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    public function down()
    {
        $this->delete(
            'user_types',
            ['name' => 'admin']
        );
        $this->delete(
            'user_types',
            ['name' => 'reporter']
        );
        $this->delete(
            'user_credentials',
            ['email' =>'digitalrights@putsbox.com',]
        );

        $this->dropTable('user_types');
        $this->dropTable('user_credentials');
    }
}