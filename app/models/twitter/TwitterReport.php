<?php

namespace app\models\twitter;

use app\constants\MigrationConstants;
use app\models\BaseModel;
use app\models\Country;
use app\models\ViolationTypes;

/**
 * This is the model class for table "email_reports".
 * @property Country $country
 * @property ViolationTypes $violation
 */
class TwitterReport extends BaseModel
{
    public $violation_type_id;
    public $data_consent;
    public static function tableName()
    {
        return MigrationConstants::TABLE_TWITTER_REPORTS;
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
