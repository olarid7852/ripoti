<?php

namespace app\widgets\modals;

use app\models\Role;
use CottaCush\Yii2\Helpers\Html;
use yii\bootstrap\ActiveForm;


/**
 * Class inviteModalWidget
 *
 * @author medinat <medinatapanpa@gmail.com>
 */
class InviteModalWidget extends ActiveFormModalWidget
{
    public $modalId;
    public $title;
    public $placeHolder;
    public $classModalNames;
    public $classNames;
    public $classButtonClose;
    public $classButtonAdd;

    public function renderContents()
    {
        $form = ActiveForm::begin(
            [
                'id' => 'invite-types-form',
                'class' => 'form-size',
                'action' => 'create-invite-types'
            ]
        );
        echo Html::beginTag('div', ['container']);
        echo Html::beginTag('div', ['class' => 'row']);
        echo Html::beginTag('div', ['class' => 'admin_role col-md-8 col-xs-12']);
        echo $form->field($this->model, 'email')
            ->textInput(['class' => 'invite-field form-control mustFill', 'placeholder' => $this->placeHolder])
            ->label('Email Address');
        echo Html::endTag('div');
        echo Html::beginTag('div', ['class' => 'admin_role col-md-4 col-xs-12']);
        echo $form->field($this->model, 'role_key')
            ->dropDownList(
                Role::getRoles(),
                [
                    'data-status' => true,
                    'class' => 'invite-field form-control',
                    'prompt' => 'Roles',
                ]
            )
            ->label('Choose a role');
        echo Html::endTag('div');
        echo Html::endTag('div');
        echo Html::endTag('div');
        $form::end();
    }
}