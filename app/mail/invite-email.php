<img src="<?= yii\helpers\ArrayHelper::getValue(Yii::$app->params, 'baseUrl') ?>images/logo.jpg"
     xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
     alt="Ripoti" class="header-logo">

<p class="body-text">Hi,</p>
<p class= body-text>
You have been invited to join the Ripoti platform.
The platform will enable you keep track of reports made by the public and 
come in where needed.
Kindly use the link below to accept your invitation. 
<p>
<div class="button-text">
    <a class="btn" href="<?= $invitationLink ?>">Accept Invitation</a>
</div>
<p class= body-text>
If you're having trouble clicking the accept invitation button, copy and paste the
URL below into your web browser <br>
    <span class=body-text2>
        <a href="<?= $invitationLink ?>" >
            <?= $invitationLink ?>
        </a>
    </span>
</p>
<style>
    .body-text {
        letter-spacing: 0.8px;
        text-align: left;
        color: #279a47;
    }
    .body-text2 {
        text-decoration: underline;
        line-height: 1.56;
        letter-spacing: 0.8px;
        text-align: left;
        color: #f79521;
    }
    .button-text {
        letter-spacing: 1.6px;
        text-align: center;
        color: #ffffff;
    }
   .header-logo {
       margin:auto;
       border-bottom: 1px solid #279a47;
   }
</style>
