<?php

namespace app\models;

use app\constants\MigrationConstants;
use app\constants\Status;
use app\models\BaseModel;
use app\models\Country;
use app\models\ViolationTypes;
use app\models\State;

/**
 * This is the model class for table "email_reports".
 * @property $id
 * @property $violation_type_id
 * @property $reporter_email
 * @property $case_description
 * @property $report_reply
 * @property $created_at
 * @property $updated_at
 * @property Country $country
 * @property ViolationTypes $violation
 * @property Role $role
 * @property Status $statusObj
 * @property UserCredential $userAuth
 */
class EmailReport extends BaseModel
{
    public $data_consent;
    public $violation_type_id;

    public static function tableName()
    {
        return MigrationConstants::TABLE_EMAIL_REPORTS;
    }

    public function getViolation()
    {
        return $this->hasOne(ViolationTypes::class, ['id' => 'violation_type_id']);
    }

    public function getCountry()
    {
        return $this->hasOne(Country::class, ['id' => 'country_id']);
    }

    public function getLocation()
    {
        return $this->country['name'];
    }

    public function getName()
    {
        return $this->name;
    }
}