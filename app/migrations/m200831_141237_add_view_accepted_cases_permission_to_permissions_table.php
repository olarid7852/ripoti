<?php

use cottacush\userauth\libs\Utils;
use yii\db\Migration;

/**
 * Class m200831_141237_add_view_accepted_cases_permission_to_permissions_table
 */
class m200831_141237_add_view_accepted_cases_permission_to_permissions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $now = Utils::getCurrentDateTime();

        $this->insert('permissions', [
            'key' => 'view-accepted-cases',
            'label' => 'View Accepted Cases',
            'status' => 'active',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->delete('permissions', [
            'key' =>'view-accepted-cases',
            'label' =>'View Accepted Cases',
            'status' => 'active',
        ]);
    }
}
