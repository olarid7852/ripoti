<?php

namespace app\widgets;

use yii\helpers\Html;

class FooterWidget extends BaseWidget
{
    public function run()
    {
        $this->beginDiv(['class'=>'main-footer']);
        echo Html::tag('p', 'Powered by Paradigm Initiative.');
        $this->endDiv();
    }
}
