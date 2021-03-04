<?php

namespace app\models;

use app\constants\MigrationConstants;
use yii\helpers\ArrayHelper;

/**
 * Class State
 * @property mixed|null name
 * @package app\models
 */
class State extends BaseModel
{

    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return MigrationConstants::TABLE_STATES;
    }

    public static function getState()
    {
        $result = State::find()->all();
        return Arrayhelper::map($result, 'id', 'name');
    }

    public static function getStatesFromCountry($country_id)
    {
        $result = self::find()->where(['country_id' => $country_id])->orderBy(['name' => SORT_ASC])->all();
        return ArrayHelper::map($result, 'id', 'name');
    }
}