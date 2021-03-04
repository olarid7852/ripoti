<?php

namespace app\models;

use app\constants\Messages;
use app\exceptions\EntityNotSavedException;
use app\libs\Utils;
use CottaCush\Yii2\Model\BaseModel as CottaCushBaseModel;

/**
 * Class BaseModel
 * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
 * @package app\models
 */
class BaseModel extends CottaCushBaseModel
{
    /**
     * @param $table
     * @param $columns
     * @param $rows
     * @param $entity
     * @throws EntityNotSavedException
     * @throws \yii\db\Exception
     */
    public static function batchInsert($table, $columns, $rows, $entity)
    {
        $db = BaseModel::getDb();
        $execution = $db->createCommand()->batchInsert($table, $columns, $rows)->execute();
        if (count($rows) !== $execution) {
            throw new EntityNotSavedException(Messages::getFailureMessage($entity, 'saved'));
        }
    }

    public function saveData($data = null)
    {
        $this->load($data);
        if (!$this->save()) {
            throw new EntityNotSavedException($this->getFirstError());
        }
    }

    /**
     * @param string $column
     * @return string|null
     * @author Maryfaith Mgbede <adaamgbede@gmail.com>
     */
    public function getStatusHTML($column = 'status')
    {
        if (isset($this->$column)) {
            return Utils::getStatusHtml(str_replace('-', ' ', ucwords($this->$column)), '');
        }
        return null;
    }

    public static function getDateRangeFilter($dateRange, $query, $table)
    {
        if ($dateRange) {
            $splited = explode(' - ', $dateRange);
            $query->andWhere(['between', $table . '.created_at', $splited[0], $splited[1]]);
        }
        return $query;
    }
}
