<?php

namespace app\widgets;

use CottaCush\Yii2\Helpers\Html;

/**
 * Class ListTitleWidget
 * @author medinat <medinatapanpa"gmail.com>
 * @package app\widgets
 */
class ListTitleWidget extends BaseWidget
{
    public $title;
    public $btn_name;
    public $btnClass;
    public $breadcrumb;

    public function run()
    {
//      HEADER
        echo Html::beginTag('div', ['class'=>'report container col-12']);
        echo Html::tag('h2', $this->title, ['class'=>'page-header ']);
        echo Html::Button(
            '<i class="fa fa-plus" aria-hidden="true"></i> New'.' '. $this->btn_name,
            ['class' => 'btn btn_violation empty-state__btn add-btn' .  $this->btnClass , 'id' => 'modalButton']
        );
        echo Html::endTag('div');

//        BREADCRUMBS
        echo Html::beginTag('div', ['class' => 'crumbs mt-1']);
        echo Html::beginTag('ul', ['class'=>'breadcrumb']);
        echo Html::beginTag('li');
        echo Html::a('Home', '/dashboard/index', ['class'=>'']);
        echo Html::endTag('li');
        echo Html::beginTag('li', ['class'=>'active']);
        echo Html::a($this->breadcrumb, '#');
        echo Html::endTag('li');
        echo Html::endTag('ul');
        echo Html::endTag('div');
    }
}