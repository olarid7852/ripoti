<?php

namespace app\models\forms;

use app\libs\Utils;
use yii\base\Model;

class BaseFormModel extends Model
{
    /**
     * @param string $column
     * @return string|null
     */
    public function getStatusHtml($column = 'status')
    {
        if (isset($this->$column)) {
            return Utils::getStatusHtml(str_replace('-', ' ', ucwords($this->$column)), '');
        }
        return null;
    }
}