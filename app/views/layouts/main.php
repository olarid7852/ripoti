<?php
/* @var $this \yii\web\View */
/* @var $content string */
use app\assets\MaterialDesignIconsAsset;
use CottaCush\Yii2\Assets\ToastrNotificationAsset;
use yii\helpers\Html;
use app\assets\AppAsset;
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
        <link rel="shortcut icon" href="/Ripoti.svg" type="image/x-icon" />
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>
    <div class="wrap">
        <div class="">
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