<?php

namespace app\models;

use app\constants\MigrationConstants;
use yii\helpers\ArrayHelper;

/**
 * @property mixed|null name
 */
class Country extends BaseModel
{
    /**
     * @inheritDoc
     */
    public function rules()
    {
        return ['country', 'required'];
    }

    public static function tableName()
    {
        return MigrationConstants::TABLE_COUNTRIES;
    }

    public static function getCountry()
    {
        $result = Country::find()->all();
        return Arrayhelper::map($result, 'id', 'name');
    }
}
