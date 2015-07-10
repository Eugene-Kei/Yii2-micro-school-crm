<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = Yii::t('app', 'About');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <h3><?= Yii::$app->config->get('CONTACT.ORGANIZATION_NAME') ?></h3>
    <p><?= Yii::$app->config->get('CONTACT.ADDRESS') ?></p>

</div>
