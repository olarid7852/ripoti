<?php

namespace app\widgets\modals;

use app\assets\EditRolePermissionAsset;
use app\constants\Messages;
use app\models\forms\AdministrationTypesForm;
use app\models\Permissions;
use app\models\Status;
use CottaCush\Yii2\Helpers\Html;
use yii\bootstrap4\Modal;
use yii\bootstrap4\ActiveForm;

/**
 * Class Administration Role Modal widget
 *
 * @author medinat <medinatapanpa@gmail.com>
 */
class AdministrationRoleModalWidget extends ActiveFormModalWidget
{
    public $modalId;
    public $title;
    public $placeHolder;
    public $classNames;
    public $classButtonSave;
    public $buttonName;
    public $footerCancel = false;
  
    public function renderContents()
    {
        EditRolePermissionAsset::register($this->view);
        echo Html::beginTag('div', ['class' => 'row role-field-display']);
        echo Html::beginTag('div', ['class' => 'admin_role col-md-8 col-xs-12']);
        echo $this->form->field($this->model, 'label')
            ->textInput(['class' => 'role-input form-control', 'placeholder' => $this->placeHolder])
            ->label('Role Name');
        echo Html::endTag('div');
        echo Html::beginTag('div', ['class' => 'invite-select col-md-4 col-xs-12']);
        $status = ['active' => ucfirst(Status::STATUS_ACTIVE), 'inactive' => ucfirst(Status::STATUS_INACTIVE)];
        echo $this->form->field($this->model, 'status')
            ->dropDownList(
                $status,
                ['data-status' => true, 'class' => 'role-input form-control']
            )
            ->label('Status ');
        echo Html::endTag('div');
        echo Html::endTag('div');
        //Checkboxes
        echo Html::beginTag('div', ['class' => 'checkbox']);
        echo Html::beginTag('div', ['class' => 'container']);
        echo $this->form->field($this->model, 'permit_all')->checkbox(['id' => 'select-all'])->label('Select All');
        echo Html::endTag('div');
        echo $this->form->field($this->model, 'permissions[]')->checkboxList(
            Permissions::getAdminPermissions(),
            ['separator' => ' ', 'class' => 'ml-3  checkbox_group']
        );
        echo Html::endTag('div');
    }
}
