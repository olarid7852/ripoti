<?php
namespace app\widgets;
use app\assets\BulkActionAsset;
use app\widgets\modals\BulkActionModalWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
/**
 * Class BulkActionWidget
 * @package app\widgets
 * @author Malomo Damilare <damilaremalomo@gmail.com>
 */
class BulkActionWidget extends BaseWidget
{
    public $form;
    public $model;
    public $actions = [];
    public $route = '';
    public function init()
    {
        BulkActionAsset::register($this->view);
        parent::init();
        echo Html::beginTag('div', ['class' => 'dropdown mb-3 d-none bulk-action-wrapper']);
        echo Html::button('Actions', [
            'class' => 'btn btn-success dropdown-toggle',
            'type' => 'button',
            'id' => 'bulk-action-btn',
            'data' => [
                'toggle' => 'dropdown'
            ]
        ]);
        $this->renderActions($this->actions);
        echo Html::endTag('div');
        echo BulkActionModalWidget::widget([
            'route' => $this->route
        ]);
    }
    /**
     * @param array $actions
     * @author Malomo Damilare <damilaremalomo@gmail.com>
     */
    private function renderActions($actions)
    {
        echo Html::beginTag('div', ['class' => 'row']);
        echo Html::beginTag('div', ['class' => 'dropdown-menu col-1', 'aria-labelledby' => 'bulk-action']);
        foreach ($actions as $action) {
            echo Html::a($action['label'], null, [
                'class' => 'dropdown-item bulk-dropdown-item font-size-nm btn-success cursor-pointer',
                'data' => [
                    'toggle' => 'modal',
                    'target' => '#bulk-action-modal',
                    'url' => ArrayHelper::getValue($action, 'url'),
                    'title' => ArrayHelper::getValue($action, 'title'),
                    'value' => ArrayHelper::getValue($action, 'value'),
                    'msg' => ArrayHelper::getValue($action, 'msg')
                ]
            ]);
        }
        echo Html::endTag('div');
        echo Html::endTag('div');
    }
}