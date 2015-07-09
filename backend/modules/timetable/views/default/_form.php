<?php

use backend\modules\timetable\Module;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\BootstrapAsset;
use yii\helpers\Html;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model backend\modules\timetable\models\Timetable */
/* @var $form yii\widgets\ActiveForm */


// подключаем библтотеку clockpicker

$clockPickerOptions = "{
              'default':'now',
              'placement':'bottom',
              'autoclose':'true',
              
            }";

$jsPath = Yii::$app->basePath . '/modules/timetable/assets/js/clockpicker.js';
$assetClockpicker = Yii::$app->assetManager->publish($jsPath);

$this->registerJsFile(
    $assetClockpicker[1], ['depends' => [yii\web\JqueryAsset::className()]]
);

$this->registerCssFile(
    Yii::$app->assetManager->publish(
        Yii::$app->basePath . '/modules/timetable/assets/css/clockpicker.css')[1],
    [
        'depends' => [BootstrapAsset::className()]
    ]
);

$this->registerCssFile(
    Yii::$app->assetManager->publish(
        Yii::$app->basePath . '/modules/timetable/assets/css/standalone.css')[1],
    [
        'depends' => [BootstrapAsset::className()]
    ]
);

$this->registerJs("jQuery('.clockpicker').clockpicker($clockPickerOptions);", View::POS_READY);
?>

<div class="timetable-form">

    <?php $form = ActiveForm::begin(['id' => 'timetable-form', 'enableClientValidation' => false]); ?>

    <div class="row">
        <div class="col-sm-3 clockpicker">
            <?= $form->field($model, 'start')->textInput() ?>
        </div>
        <div class=" col-sm-3 clockpicker">
            <?= $form->field($model, 'end')->textInput() ?>

        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'week_day')->textInput()->dropDownList($model->WeekArray) ?>

        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'group_id')->textInput()->dropDownList($groupsArray) ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Module::t('timetable-admin',
            'Create') : Module::t('timetable-admin', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-primary btn-large' : 'btn btn-success btn-large']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
