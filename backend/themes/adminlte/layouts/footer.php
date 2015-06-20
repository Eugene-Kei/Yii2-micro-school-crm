<?php
use yii\helpers\Html;

?>

<footer class="main-footer">
    <small>Copyright &copy; 2015<?= (date('Y') > '2015') ? '-' . date('Y') : '' ?>
        <?= Html::a(Yii::$app->name, ['url' => Yii::$app->homeUrl]) ?>.
    </small>
</footer>
