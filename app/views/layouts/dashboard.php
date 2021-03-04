<?php

/* @var $this \yii\web\View */

/* @var $content string */

use app\assets\MaterialDesignIconsAsset;
use app\models\Admin;
use app\widgets\SideMenuWidget;
use CottaCush\Yii2\Assets\ToastrNotificationAsset;
use yii\helpers\Html;
use app\assets\AppAsset;
use app\widgets\SidebarWidget;
use app\widgets\TopbarWidget;
use app\assets\FontAwesomeAsset;

AppAsset::register($this);
FontAwesomeAsset::register($this);
ToastrNotificationAsset::register($this);
MaterialDesignIconsAsset::register($this);
?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">

    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <link rel="shortcut icon" href="/Ripoti.svg" type="image/x-icon"/>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>

    <body>
    <?php
    $user = Yii::$app->user->getIdentity();
    echo TopbarWidget::widget(['user' => $user]);
    echo SidebarWidget::widget();
    ?>

    <div class="wrap">
        <div class="mt-2 page-wrapper">
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">

    </footer>
    <?= $this->context->showFlashMessages(); ?>

    <?php $this->endBody() ?>
    </body>

    </html>
<?php $this->endPage() ?>