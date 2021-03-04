<?php

namespace app\models;

use app\constants\MigrationConstants;
use yii\helpers\ArrayHelper;

/**
 * Class Permissions
 * femimeduna@gmail.com
 * @property mixed|null status
 * @property mixed|null names
 * @package app\models
 */
class Permissions extends BaseModel
{
    const MANAGE_INVITE = 'access-invite';
    const MANAGE_NOTIFICATION = 'manage-notification';
    const MANAGE_CASES = 'manage-cases';
    const MANAGE_VIOLATION_TYPE = 'manage-violation-type';
    const MANAGE_USERS = 'manage-users';
    const MANAGE_DASHBOARD = 'access-menu';
    const MANAGE_REPORT = 'access-report';
    const MANAGE_ADMIN = 'manage-role';
    const CLOSE_CASE = 'close-case';

    public static function tableName()
    {
        return MigrationConstants::TABLE_PERMISSIONS;
    }

    public static function getAdminPermissions()
    {
        $result = self::find()->all();
        return Arrayhelper::map($result, 'id', 'label');
    }
}
