<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $email frontend\models\SignupForm */
/* @var $password frontend\models\SignupForm */

?>
<div class="password-reset">
    <p>Hello!</p>
    <p>Thank you for registration on <?= Html::a(Html::encode(Yii::$app->urlManager->hostInfo), Yii::$app->urlManager->hostInfo) ?>!</p>

    <p>
        Your login (phone): <?=$phone?><br />
        Your password: <?=$password?><br />
    </p>

</div>
