<?php

namespace app\widgets;

use CottaCush\Yii2\Helpers\Html;
use yii\helpers\Url;
use app\assets\MaterialDesignIconsAsset;

/**
 * Class EmptyStateWidget
 * @author Olajide Oye <jide@cottacush.com>
 * @package app\widgets
 */
class EmptyStateWidget extends BaseWidget
{
    public $icon;
    public $title;
    public $description;

    public function run()
    {
        MaterialDesignIconsAsset::register($this->view);
        echo Html::beginTag('section', ['class' => 'empty-state']);
        echo Html::beginTag('div', ['class' => 'text-center mt-3']);
        echo Html::tag('span', $this->icon, ['class' => 'material-icons empty-state__icon dashboard-logo']);
        echo Html::tag('p', $this->description, ['class' => 'note empty-state__description']);
        echo Html::endTag('section');
        
    }
}
