<?php

namespace app\models;

use app\constants\MigrationConstants;
use app\models\forms\ReportTypesForm;
use yii\helpers\ArrayHelper;

/**
 * Class ViolationTypes
 * @property mixed|null status
 * @property mixed|null names
 * @property ReportTypesForm report
 * @package app\models
 */
class ViolationTypes extends BaseModel
{
    public function rules()
    {
        return [
            [['names', 'status'], 'safe' ],
        ];
    }

    public static function tableName()
    {
        return MigrationConstants::TABLE_VIOLATION_TYPES;
    }


    public static function getViolationTypes()
    {
        $result = ViolationTypes::find()->where(['status' => Status::STATUS_ACTIVE])->all();
        return Arrayhelper::map($result, 'id', 'names');
    }

    public function getReport()
    {
        return $this->hasMany(ReportTypesForm::class, ['violation_type_id' => 'id']);
    }

    public function getReportViolations()
    {
        $reports = $this->getReport()->all();
        return count($reports);
    }

    public function attributeLabels()
    {
        return ['names' => 'Type of Violation'];
    }

    public static function checkDuplicateViolation($violation)
    {
        return self::find()->where(['names' => $violation])->exists();
    }

}
