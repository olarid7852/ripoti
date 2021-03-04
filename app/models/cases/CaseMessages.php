<?php

namespace app\models\cases;

use app\constants\MigrationConstants;
use app\models\BaseModel;

class CaseMessages extends BaseModel
{
    public static function tableName()
    {
        return MigrationConstants::TABLE_CASE_MESSAGES;
    }

    public function rules()
    {
        return [
            [['sender_id'], 'safe'],
        ];
    }

    public function getCaseDetails()
    {
        return $this->hasOne(CaseAssignment::class, ['id' => 'case_id']);
    }

    public static function getMessages($caseId = [])
    {
        return self::find()->where(['case_id' => $caseId])->all();
    }
}
