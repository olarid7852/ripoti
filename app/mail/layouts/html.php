<?php

use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style type="text/css">
        body {
            font-family: 'Raleway', sans-serif;
            color: #646363;
            font-size: 16px;
        }

        a {
            color: #f79521;
        }

        h1 {
            font-weight: 900;
            color: #646363;
        }

        .btn {
            display: inline-block;
            border-radius: 4px;
            text-decoration: none;
            color: #FFFFFF !important;
            background: #f79521;
            padding: 20px 77px;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 0 32px 0 rgba(136, 152, 170, 0.15);
        }

        img {
            padding-top: 50.9px;
            display: block;
            width: 100%;
            max-width: 50%;
        }

    </style>
</head>
<body>
<?php $this->beginBody() ?>
<table style="padding: 0; width: 100%;">
    <tr>
        <td>
            <table style="width: 600px; padding: 0; border: 1px solid #EDEDED; border-spacing: 0px; margin: 0px auto;">
                <tr>
                    <td style="background-color: #FFFFFF;padding: 30px 70px; font-size: 16px; line-height: 1.5;">

                        <?= $content ?>

                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
