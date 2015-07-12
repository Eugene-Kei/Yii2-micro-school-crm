<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $email frontend\models\SignupForm */
/* @var $password frontend\models\SignupForm */

?>
<div class="password-reset">
    <p>Здравствуйте!</p>

    <p>Благодарим за регистрацию на <?= Html::a(Html::encode(Yii::$app->urlManager->hostInfo),
            Yii::$app->urlManager->hostInfo) ?>!</p>

    <p>
        Данные для входа в ваш личный кабинет
    </p>

    <p>
        Логин (телефон): <?= $phone ?><br/>
        Пароль: <?= $password ?><br/>
    </p>

</div>
