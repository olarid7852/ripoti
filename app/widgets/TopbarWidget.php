<?php

namespace app\widgets;

use app\models\Admin;
use yii\bootstrap4\Html;
use yii\helpers\Url;

/**
 * Class TopbarWidget
 * @package app\widgets
 * @property Admin $user
 */

class TopbarWidget extends BaseWidget
{
    public $user;
    public function run()
    {
        echo Html::beginTag('div', ['class' => 'container-fluid bar d-flex justify-content-end']);
        echo Html::beginTag('div', ['class' => 'dropdown']);
        echo Html::beginTag('div', ['class' => 'logout container mr-2']);
        echo Html::tag(
            'div',
            Html::tag(
                'span',
                'account_circle',
                ['class' => 'material-icons profile-icon']
            )
        );
        echo Html::beginTag('div', ['class' => 'admin-id', 'data-toggle' => 'dropdown']);
        echo Html::a(
            $this->user->getFullName()
        );
        echo Html::endTag('div');
        echo Html::beginTag('a', ['class' => 'dropdown-toggle sign-out']);
        echo Html::endTag('a');
        echo Html::beginTag('div', ['class' => 'dropdown-menu']);
        echo Html::a('Sign Out', Url::toRoute('default/logout'), ['class' => 'dropdown-item']);
        Url::toRoute('default/logout');
        echo Html::endTag('div');
        echo Html::endTag('div');
        echo Html::endTag('div');
        echo Html::endTag('div');
    }
}