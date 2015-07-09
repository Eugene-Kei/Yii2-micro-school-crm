<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\pay\models\PaySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pay-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'ticket_id') ?>

    <?= $form->field($model, 'comment') ?>

    <?= $form->field($model, 'current_cost') ?>

    <?php // echo $form->field($model, 'cash') ?>

    <?php // echo $form->field($model, 'bonus_cash') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
