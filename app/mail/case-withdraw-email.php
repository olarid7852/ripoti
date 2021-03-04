<img src="<?= yii\helpers\ArrayHelper::getValue(Yii::$app->params, 'baseUrl') ?>images/logo.jpg"
     xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
     alt="Ripoti" class="header-logo">

<p class="body-text">Hi <?= $name ?>,</p>
<p class= body-text>
    Ripoti has reassigned case DRP <?= $case ?> to another administrator, and you have been withdrawn from the case. Please see summary of case
    below.</p>
<p class="body-report"> <?= $summary ?> </p>
<p class= body-text>
    To view more information about case(s) assigned to you please follow the link below: <br>
    <span class=body-text2>
        <a href="<?= $assigneeCaseLink ?>" >
            <?= $assigneeCaseLink ?>
        </a>
    </span>
</p>
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
        letter-spacing: 0.8px;
        text-align: left;
        color: #5f5f5f;
        border-radius: 6px;
        margin: 0 2rem;
        padding: 1.5rem;
        background-color: rgba(247, 149, 33, 0.23);
    }
    .header-logo {
        margin:auto;
        border-bottom: 1px solid #279a47;
    }
    .body-text2 {
        text-decoration: underline;
        line-height: 1.56;
        letter-spacing: 0.8px;
        text-align: left;
        color: #f79521;
    }
</style>
