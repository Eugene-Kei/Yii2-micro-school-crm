<?php

use kartik\date\DatePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use backend\modules\pay\Pay;

/* @var $this yii\web\View */
/* @var $model backend\modules\pay\models\Pay */
/* @var $form yii\widgets\ActiveForm */
/* @var $profile common\models\Profile */
/* @var $tickets backend\modules\ticket\models\SeasonTicket */
?>

<div class="pay-form">

    <?php
    $form = ActiveForm::begin([
        'id' => 'pay-form',
        'enableClientValidation' => true,
    ]);
    ?>

    <div class="row">
        <div class="col-sm-12">
            <?=
            $form->field($model, 'user_id')->dropDownList(
                [
                    $profile->user_id => $profile->getFullName()
                ], [
                'readonly' => 'readonly'
                ]
            )
            ?>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?=
            $form->field($model, 'ticket_id')->dropDownList($tickets,
                ['prompt' => Pay::t('pay-admin', 'Without subscription')])
                ->hint(Pay::t('pay-admin', 'the name of the subscription - the cost'))
            ?>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?=
            $form->field($model, 'startdate')->widget(DatePicker::className(), [
                    'options' => ['value' => date('d.m.Y')],
                    'id' => 'startdate',
                    'language' => 'ru',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd.mm.yyyy'
                    ]
                ]
            );
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?=
            $form->field($model, 'groups')->checkboxList($groups)
            ?>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?php
            $options = [];
            if (0 > $profile->balance) {
                $options['class'] = 'text-danger';
            } elseif (0 < $profile->balance) {
                $options['class'] = 'text-success';
            }
            ?>
            <?=
            $form->field($model, 'cash', [
                'hintOptions' => $options
            ])->textInput()->hint(Pay::t('pay-admin', 'User cash: {sum}', ['sum' =>  $profile->balance]))
            ?>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?=
            $form->field($model, 'bonus_cash', [
                'hintOptions' => [
                    'class' => 0 < $profile->bonus_balance ? 'text-success' : 'help-block'
                ]
            ])->textInput()->hint(Pay::t('pay-admin', 'User has bonus cash: {sum}', ['sum' =>  $profile->bonus_balance]))
            ?>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'comment')->textArea(['maxlength' => 255]) ?>

        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Pay::t('pay-admin', 'Create') : Pay::t('pay-admin', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-primary btn-large' : 'btn btn-success btn-large']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
