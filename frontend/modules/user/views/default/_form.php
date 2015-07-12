<?php

use vova07\fileapi\Widget;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\date\DatePicker;
use frontend\modules\user\User;

/* @var $this yii\web\View */
/* @var $profile common\models\Profile */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php
    $form = ActiveForm::begin(
                    [
                        'id' => 'user-form', 
                    ]
    );
    ?>

    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($profile, 'surname') ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($profile, 'name') ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($profile, 'middle_name') ?>
        </div>
    </div>
    <div class="row">

        <div class="col-sm-4">
            <?=
            $form->field($profile, 'birthday')->widget(DatePicker::className(), [
//            'options'       => ['value' => date('d.m.Y')],
                'id'            => 'birthday',
                'type'          => DatePicker::TYPE_INPUT,
                'language'      => 'ru',
                'pluginOptions' => [
                    'autoclose' => true,
                    'startView' => 'decade',
                    'format'    => 'yyyy-mm-dd'
                ]
                    ]
            );
            ?>
        </div>
        <div class="col-sm-4">
            <?php
            echo $form->field($profile, 'gender')->dropDownList(
                    $profile->getGenderArray(), ['prompt' => User::t('user-frontend', 'Choose a gender')]
            );
            ?>
        </div>

        <div class="col-sm-4">
            <?=
            $form->field($profile, 'avatar_url')->widget(Widget::className(), [
                    'settings'         => [
                        'url' => ['fileapi-upload']
                    ],
                    'crop'             => true,
                    'cropResizeWidth'  => 100,
                    'cropResizeHeight' => 100
                ]
            )
            ?>
        </div>


    </div>
    <div class="row">
        <div class="col-sm-12">
            <?=
            Html::submitButton(User::t('user-frontend', 'Update'), [
                'class' => 'btn btn-success btn-large'
                    ]
            )
            ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>

</div>
