<?php

use eugenekei\news\Module;
use vova07\imperavi\Widget as Imperavi;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model eugenekei\news\models\News */
/* @var $form yii\widgets\ActiveForm */

$imperaviPlugins = [
    'fontsize',
    'fontcolor',
    'table',
    'video',
    'fullscreen',
    'imagemanager',
];
?>

<div class="news-form">

    <?php $form = ActiveForm::begin(['id' => 'news-form']); ?>



    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'title')->textInput(['maxlength' => 100]) ?>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'annonce')->widget(Imperavi::className(), [
                'id' => 'imperavi-widget-annonce',
                'settings' => [
                    'lang' => 'ru',
                    'minHeight' => 200,
                    'maxHeight' => 400,
                    'limiter' => 4,
                    'imageManagerJson' => Url::to(['admin/images-get']),
                    'imageUpload' => Url::to(['admin/image-upload']),
                    'imageDelete' => ['url' => Url::to(['admin/image-delete'])],
                    'plugins' => $imperaviPlugins
                ]
            ]) ?>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'content')->widget(Imperavi::className(), [
                'id' => 'imperavi-widget-content',
                'settings' => [
                    'lang' => 'ru',
                    'minHeight' => 200,
                    'maxHeight' => 400,
                    'imageManagerJson' => Url::to(['admin/images-get']),
                    'imageUpload' => Url::to(['admin/image-upload']),
                    'imageDelete' => Url::to(['admin/image-delete']),
                    'plugins' => $imperaviPlugins
                ]
            ]) ?>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'status')->dropDownList($model->getStatusArray()) ?>

        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Module::t('eugenekei-news', 'Create') : Module::t('eugenekei-news', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-primary btn-large' : 'btn btn-success btn-large']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
