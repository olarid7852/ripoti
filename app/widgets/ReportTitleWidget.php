<?php

namespace app\widgets;

use CottaCush\Yii2\Helpers\Html;

/**
* Class ListTitleWidget
* @author ireti <iretiogedengbe "gmail.com>
* @package app\widgets
*/

class ReportTitleWidget extends BaseWidget
 {
    /**
     * @var mixed
     */
    public $file;
    public $fileName;
    public $urlName;

    public function run()
    {
        //BREADCRUMBS
        echo Html::beginTag('div', ['class' => 'crumbs mt-1']);
        echo Html::beginTag('ul', ['class' => 'breadcrumb']);
        echo Html::beginTag('li');
        echo Html::a('Home', '/dashboard/index');
        echo Html::endTag('li');
        echo Html::beginTag('li', ['class' => 'active']);
        echo Html::a($this->fileName,  $this->urlName);
        echo Html::endTag('li');
        echo Html::beginTag('li');
        echo Html::a($this->file, '#');
        echo Html::endTag('li');
        echo Html::endTag('ul');
        echo Html::endTag('div');
        echo Html::endTag('section');
    }
}