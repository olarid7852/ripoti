<img src="<?= yii\helpers\ArrayHelper::getValue(Yii::$app->params, 'baseUrl') ?>images/logo.jpg"
     xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
     alt="Ripoti" class="header-logo">

<p class="body-text">Hi <?= $name ?>,</p>
<p class="body-text"> <?= $reportReply ?> </p>
<p class= body-text>
    Regards,<br>
    Paradigm Initiative
</p>
<style>
    .body-text {
        letter-spacing: 0.8px;
        text-align: left;
        color: #279a47;
    }
    .body-report {
        letter-spacing: 0.5px;
        text-align: left;
        color: #5f5f5f;
        border-radius: 6px;
        margin: 0 1rem;
        padding: 1rem;
    }
    .header-logo {
        margin:auto;
        border-bottom: 1px solid #279a47;
    }
</style>
