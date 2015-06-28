<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use backend\modules\group\Module;

/* @var $this yii\web\View */
/* @var $model backend\modules\group\models\Group */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="group-form">

    <?php $form = ActiveForm::begin(['id' => 'group-form']); ?>

    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'name')->textInput(['maxlength' => 80]) ?>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'description')->textArea(['maxlength' => 255]) ?>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'status')->dropDownList(\backend\modules\group\models\Group::getStatusArray()) ?>

        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Module::t('group-admin', 'Create') : Module::t('group-admin',
            'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-primary btn-large' : 'btn btn-success btn-large']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
