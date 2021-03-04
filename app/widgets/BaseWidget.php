<?php
namespace app\widgets;
use yii\base\Widget;
use yii\helpers\Html;
/**
 * Class BaseWidget
 * @author Bolade Oye <boade@cottacush.com>
 * @package app\widgets
 */
class BaseWidget extends Widget
{
    public function beginRow($class = '')
    {
        echo Html::beginTag('div', ['class' => 'row ' . $class]);
    }
    public function beginDiv($options)
    {
        echo Html::beginTag('div', $options);
    }
    public function endDiv()
    {
        echo Html::endTag('div');
    }
    public function beginColumn($class = 'col-md-6')
    {
        echo Html::beginTag('div', ['class' => $class]);
    }
}
