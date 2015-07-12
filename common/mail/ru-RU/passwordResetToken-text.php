<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
Здравствуйте!

Был заказан сброс пароля от вашего аккаунта на сайте <?= Yii::$app->urlManager->hostInfo?>.
Для продолжения, перейдите по ссылке:

<?= $resetLink ?>
