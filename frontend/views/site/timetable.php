<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Timetable');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-timetable">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('//layouts/_staticTable') ?>
</div>