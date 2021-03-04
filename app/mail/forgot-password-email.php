<img src="<?= yii\helpers\ArrayHelper::getValue(Yii::$app->params, 'baseUrl') ?>images/logo.jpg"
     xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
     alt="Ripoti" class="header-logo">

<p class="body-text">Hi,</p>
<p class= body-text>
    We got a request to reset your password.Please use the button below to
    reset it.This password reset is only valid for the next 24 hours
<p>
<div class="button-text">
    <a class="btn" href="<?= $verificationLink ?>">Reset Password</a>
</div>
<p class= body-text>
    If you did not request for a password reset,please ignore this email.
    Ignoring this email will mean your password will not be changed.For any questions please'
    <span class="body-text2">contact us.</span> <br>Thanks
</p>
<p class= body-text>
    If you are having trouble clicking the password reset button, 
    copy and paste the URL below into your web browser <br>
    <span class=body-text2>
        <a href="<?= $verificationLink ?>" >
            <?= $verificationLink ?>
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

