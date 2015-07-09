<?php

use backend\modules\user\Module;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use vova07\fileapi\Widget as FileAPI;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $profile common\models\Profile */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['id' => 'user-form', 'enableClientValidation' => true]); ?>



    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-3">
                    <?= $form->field($profile, 'surname')->textInput() ?>

                </div>
                <div class="col-sm-3">
                    <?= $form->field($profile, 'name')->textInput() ?>

                </div>
                <div class="col-sm-3">
                    <?= $form->field($profile, 'middle_name')->textInput() ?>

                </div>
                <div class="col-sm-3">
                    <?= $form->field($user, 'phone')->textInput() ?>

                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <?= $form->field($user, 'email')->textInput() ?>

                </div>
                <div class="col-sm-3">
                    <?= $form->field($profile, 'gender')->dropDownList($profile->getGenderArray(), ['prompt' => '']) ?>

                </div>
                <div class="col-sm-3">
                    <?= $form->field($profile, 'birthday', ['inputOptions' => ['class' => 'form-control']])
                        ->widget(DatePicker::className(), [
                            'pluginOptions' => [
                                'format' => 'yyyy-mm-dd',
                                'startView' => 'decade',
                                'autoclose' => true,
                                'todayBtn' => true,
                                'todayHighlight' => true,
                                'startDate' => '1905-01-01',
                                'endDate' => date('Y-m-d'),
                            ]
                        ]) ?>

                </div>
                <div class="col-sm-3">
                    <?= $form->field($profile, 'user_affiliate_id')->widget(Select2::className(), [
                        'id' => 'user_affiliate_id',
                        'options' => [
                            'placeholder' => empty($profile->fullName) ? Yii::t('app', 'Start typing the name or surname') : '',
                            'class' => 'form-control',
                        ],
                        'language' => 'ru',
                        'data' => [$profile->profileAffiliate->user_id => $profile->profileAffiliate->fullName],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 2,
                            'ajax' => [
                                'url' => Url::to(['affiliate']),
                                'dely' => 250,
                                'type' => 'post',
                                'dataType' => 'json',
                                'data' => new JsExpression('function(term,page) { return {search:term}; }'),
                                'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
                            ],
                        ],
                    ]) ?>

                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <?= $form->field($profile, 'balance')->textInput() ?>

                </div>
                <div class="col-sm-3">
                    <?= $form->field($profile, 'bonus_balance')->textInput() ?>

                </div>
                <div class="col-sm-3">
                    <?= $form->field($user, 'status')->dropDownList($user->getStatusArray(), ['prompt' => '']) ?>

                </div>
                <div class="col-sm-3">


                    <?= $form->field($profile, 'avatar_url')->widget(
                        FileAPI::className(),
                        [
                            'crop' => true,
                            'cropResizeWidth' => 100,
                            'cropResizeHeight' => 100,
                            'settings' => [
                                'url' => ['fileapi-upload'],
                                'imageSize' => [
                                    'minWidth' => 100,
                                    'minHeight' => 100
                                ]
                            ]
                        ]
                    ) ?>
                </div>
            </div>

        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($user->isNewRecord ? Module::t('user-admin', 'Create') : Module::t('user-admin',
            'Update'),
            ['class' => $user->isNewRecord ? 'btn btn-primary btn-large' : 'btn btn-success btn-large']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
