<?php

namespace app\models;

use app\constants\MigrationConstants;
use cottacush\rbac\models\RolePermission;
use Yii;
use yii\caching\TagDependency;
use yii\helpers\ArrayHelper;
use app\constants\Status;

/**
 * This is the model class for table "roles".
 *
 * @property integer $id
 * @property string $key
 * @property string $label
 * @property integer $is_active
 * @property string $created_at
 * @property string $updated_at
 *
 */
class Role extends BaseModel
{
    public $permissions;
    public $permit_all;

    const KEY_PERMISSIONS = 'permissions';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return MigrationConstants::TABLE_ROLES;
    }

    public function rules()
    {
        return [
            [['label'], 'required'],
            ['status', 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'label' => 'Add New Role',
        ];
    }

    public static function getRoles()
    {
        $result = Role::find()
            ->where(['=', MigrationConstants::TABLE_ROLES  . '.status', Status::STATUS_ACTIVE])
            ->all();
        return Arrayhelper::map($result, 'key', 'label');
    }

    /**
     * @return \yii\db\ActiveQuery
     * @author Malomo Damilare <damilaremalomo@gmail.com>
     */
    public function getRolePermissions()
    {
        return $this->hasMany(RolePermission::class, ['role_id' => 'id']);
    }
    /**
     * @return array
     * @author Malomo Damilare <damilaremalomo@gmail.com>
     */
    public function getPermissions()
    {
        return ArrayHelper::getColumn($this->rolePermissions, 'permission_id');
    }

    /**
     * @return \yii\db\ActiveQuery
     * @author Malomo Damilare <damilaremalomo@gmail.com>
     */
    public static function getRolesQuery()
    {
        return self::find()->joinWith(['rolePermissions'])
            ->where(['!=', Role::tableName() . '.status', Status::STATUS_DELETED]);
    }

    /**
     * @param $role
     * @return bool
     * @author femi meduna <femimeduna@gmail.com>
     */
    public static function checkDuplicateRole($role)
    {
        return self::find()->where(['label' => $role])->exists();
    }

    public function getPermissionsAssignedToRole()
    {
        return $this->hasMany(Permissions::class, ['id' => 'permission_id'])
            ->viaTable(RolePermission::tableName(), ['role_id' => 'id'])
            ->onCondition([Permissions::tableName() . '.status' => Status::STATUS_ACTIVE])
            ->asArray()
            ->all();
    }

    public function getCachedPermissions()
    {
        $key = self::KEY_PERMISSIONS . '-' . $this->id;
        $cache = Yii::$app->cache;
        $permissions = $cache->get($key);
        if ($permissions === false) {
            $permissions = $this->getPermissionsAssignedToRole();
            $dependency = new TagDependency(['tags' => self::KEY_PERMISSIONS]);
            $cache->set($key, $permissions, Yii::$app->params['defaultCacheTimeout'], $dependency);
        }
        return $permissions;
    }
}
