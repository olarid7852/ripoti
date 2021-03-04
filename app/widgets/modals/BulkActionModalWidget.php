<?php
namespace app\widgets\modals;
use yii\helpers\Html;
/**
 * Class BulkActionModalWidget
 * @package app\widgets
 * @author Malomo Damilare <damilaremalomo@gmail.com>
 */
class BulkActionModalWidget extends ActiveFormModalWidget
{
    public $nameAttribute = 'name';
    public $idAttribute = 'id';
    public function renderContents()
    {
        echo Html::tag('p');
        echo Html::hiddenInput('status', '');
    }
}