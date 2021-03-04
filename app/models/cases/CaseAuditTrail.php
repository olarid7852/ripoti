<?php

namespace app\models\cases;

use app\constants\MigrationConstants;
use app\models\BaseModel;
use app\models\forms\CasesTypesForm;

class CaseAuditTrail extends BaseModel
{
    public static function tableName()
    {
        return MigrationConstants::TABLE_CASE_HISTORY;
    }

    public function getCaseDetails()
    {
        return $this->hasOne(CasesTypesForm::class, ['id' => 'case_id']);
    }

    public function isActorRole()
    {
        $user = \Yii::$app->user->identity;
        return $user->role_key;
    }

    public function isActor()
    {
        return \Yii::$app->user->identity;
    }

    public function isRole()
    {
        $role = $this->role;
        return ucwords(explode('-', $role));
    }
}