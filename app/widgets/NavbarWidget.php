<?php

namespace app\widgets;

use app\assets\NavToggleAsset;
use yii\helpers\Html;

class NavbarWidget extends BaseWidget
{
    public function run()
    {
        NavToggleAsset::register($this->view);

        $this->beginDiv(['class' => 'main-nav-bar']);
        $this->beginDiv(['id' => 'nav-logo']);
        echo Html::a( Html::img('/images/ripotii.png'), '/site/index', ['alt' => 'My logo']);
        $this->endDiv();
//NAVIGATION LINKS
        echo Html::beginTag('nav', ['class' => 'navigation d-flex']);
        echo Html::beginTag('ul');
        echo Html::beginTag('li');
        echo Html::a(
            'about us',
            '/site/index'
        );
        echo Html::endTag('li');
        echo Html::beginTag('li');
        echo Html::a(
            'what can be reported?',
            '/site/violations'
        );
        echo Html::endTag('li');
        echo Html::beginTag('li');
        echo Html::a(
            'cases',
            '/site/cases'
        );
        echo Html::endTag('li');
        echo Html::beginTag('li');
        echo Html::a(
            'contact us',
            '/site/contact'
        );
        echo Html::endTag('li');
        echo Html::beginTag('li');
        echo Html::a(
            'MAKE A REPORT',
            '/site/report-form',
            ['class' => 'make-a-report']
        );
        echo Html::endTag('li');
        echo Html::endTag('ul');
        echo Html::endTag('nav');
//END OF NAVIGATION LINKS
//BURGER FOR RESPONSIVENESS
        $this->beginDiv(['class' => 'nav-toggle align-self-center']);
        echo Html::beginTag(
            'i',
            ['class' => 'fa fa-bars nav-icon']
        );
        echo Html::endTag('i');
        $this->endDiv();
//END OF BURGER FOR RESPONSIVENESS
        $this->endDiv();
    }
}
