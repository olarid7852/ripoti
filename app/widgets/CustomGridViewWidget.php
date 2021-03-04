<?php

namespace app\widgets;

use CottaCush\Yii2\Widgets\GridViewWidget;

/**
 * Class CustomGridViewWidget
 * @author medinat <medinatapanpa@gmail.com>
 * @package app\widgets
 */
class CustomGridViewWidget extends GridViewWidget
{
    public $title ;
    public $emptyStateIcon;
    public $emptyStateTitle;
    public $emptyStateDescription;
    public $emptyStateOptions = [];
    public $tableOptions = ['class' => 'table align-items-center table-flush table-striped table-sm '];

    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    public function init()
    {

        parent::init();
        if (!$this->dataProvider->getTotalCount()) {
            $this->emptyText = EmptyStateWidget::widget(
                [
                'icon' => $this->emptyStateIcon,
                'title' => $this->emptyStateTitle,
                'description' => $this->emptyStateDescription,
                ]
            );
        }
    }
}
