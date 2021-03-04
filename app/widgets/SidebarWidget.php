<?php

namespace app\widgets;

use app\models\Admin;
use yii\helpers\Html;
use app\models\forms\AdministrationTypesForm;
use yii\bootstrap4\ActiveForm;

class SidebarWidget extends BaseWidget
{
    public $model;

    public function run()
    {
        $user = \Yii::$app->user->identity;
        $this->beginDiv(['class' => 'bar1']);
        $this->beginDiv(['class' => 'sidebar-nav items']);
        echo Html::img('\images\logo-3.png', ['class' => 'imgs d-none d-lg-block']);
        echo Html::img('\images\Rectangle 1925@2x.jpg', ['class' => 'imgs d-block d-lg-none']);
        $this->beginDiv(['class' => 'list']);
        $this->beginDiv(['class' => 'd-item']);
        echo Html::a('dashboard', '/dashboard/index', ['class' => 'material-icons d-icon',]);
        echo Html::a('DASHBOARD', '/dashboard/index', ['class' => 'd-text']);
        $this->endDiv();
        if ($user->role_key != Admin::SUPER_ADMIN) {
            $this->beginDiv(['class' => 'd-item']);
            echo Html::a('shop', '/case/actor-list-view', ['class' => 'material-icons d-icon']);
            echo Html::a('CASES', '/case/actor-list-view', ['class' => 'd-text']);
            $this->endDiv();
        }
        if ($user->role_key === Admin::SUPER_ADMIN) {
            $this->beginDiv(['class' => 'd-item']);
            echo Html::a('shop', '/case/index', ['class' => 'material-icons d-icon']);
            echo Html::a('CASES', '/case/index', ['class' => 'd-text']);
            $this->endDiv();
        }
        $this->beginDiv(['class' => 'd-item']);
        echo Html::a('assignment', '/report/index', ['class' => 'material-icons d-icon']);
        echo Html::a('REPORTS', '/report/index', ['class' => 'd-text']);
        $this->endDiv();
        $this->beginDiv(['class' => 'd-item']);
        echo Html::a('new_releases', '/violation-type/index', ['class' => 'material-icons d-icon']);
        echo Html::a('VIOLATION TYPES', '/violation-type/index', ['class' => 'd-text']);
        $this->endDiv();
        $this->beginDiv(['class' => 'd-item']);
        echo Html::a('question_answer', '/invite/index', ['class' => 'material-icons d-icon']);
        echo Html::a('INVITES', '/invite/index', ['class' => 'd-text']);
        $this->endDiv();
        $this->beginDiv(['class' => 'd-item']);
        echo Html::a('assignment_ind', '/users/index', ['class' => 'material-icons d-icon']);
        echo Html::a('USERS', '/users/index', ['class' => 'd-text']);
        $this->endDiv();
        $this->beginDiv(['class' => 'd-item']);
        echo Html::a('group', '/administration/index', ['class' => 'material-icons d-icon']);
        echo Html::a('ADMINISTRATION', '/administration/index', ['class' => 'd-text']);
        $this->endDiv();
        $this->beginDiv(['class' => 'd-item d-none']);
        echo Html::a('add_alert', '#', ['class' => 'material-icons d-icon']);
        echo Html::a('NOTIFICATIONS', '#', ['class' => 'd-text']);
        $this->endDiv();
        $this->endDiv();
        $this->endDiv();
        $this->endDiv();
    }
}