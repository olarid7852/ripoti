<?php

namespace app\widgets\modals;

use app\models\Role;
use yii\bootstrap4\ActiveForm;

/**
 * Class UsersViewFormWidget
 * @package app\widgets\modals
 */
class UsersViewFormWidget extends ActiveFormModalWidget
{
    public $userCredential;

    public function renderContents()
    {
        $form = ActiveForm::begin(
            [
                'id' => 'user-types-form',
                'class' => 'user-form-group',
                'fieldConfig' => [
                    'template' => "<div class='form-group row'>
                                    <div class='col-sm-4 col-xs-12'>{label}</div>
                                    <div class='text-danger col-sm-8 col-xs-12'>{input}{error}</div></div>",
                    'labelOptions' => ['class' => 'control-label'],
                ]
            ]
        );
        echo $form->field($this->model, 'first_name')->textInput(['class' => 'user-form form-control']);
        echo $form->field($this->model, 'last_name')->textInput(['class' => 'user-form form-control']);
        echo $form->field($this->model, 'role_key')->dropDownList(
            Role::getRoles(),
            [
                'data-status' => true,
                'class' => 'user-form form-control'
            ]
        );
        echo $form->field($this->model, 'user_auth_id')->hiddenInput()->label(false);
        echo $form->field($this->userCredential, 'email')->textInput(['class' => 'user-form form-control']);
        $form::end();
    }
}
