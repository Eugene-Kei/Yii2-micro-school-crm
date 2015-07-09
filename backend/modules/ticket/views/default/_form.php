<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\modules\ticket\Ticket;

?>

<div class="col-sm-12"><?=$popoverInfo?></div>
<div class="season-ticket-form">

    <?php $form = ActiveForm::begin(['id' => 'season-ticket-form']); ?>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'title')->textInput(['maxlength' => 80]) ?>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'description')->textArea(['maxlength' => 255]) ?>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'cost')->textInput() ?>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'amount')->textInput() ?>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'limit_format')->textInput()->dropDownList($model->getLimitFormatArray())
                    ->hint(Ticket::t('ticket', 'Time limit - unit'))
            ?>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'limit_value')->textInput()
                    ->hint(Ticket::t('ticket', 'Time limit - the number of such units'))
            ?>

        </div>
    </div>
    <div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? Ticket::t('ticket', 'Create') : Ticket::t('ticket', 'Update'),
        ['class' => $model->isNewRecord ? 'btn btn-primary btn-large' : 'btn btn-success btn-large']) ?>
    </div>
<?php ActiveForm::end(); ?>

</div>
