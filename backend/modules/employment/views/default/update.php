<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\modules\timetable\widgets\DepDrop;
use yii\helpers\Url;
use kartik\date\DatePicker;
use backend\modules\employment\Employment;

/* @var $this yii\web\View */
/* @var $model backend\modules\employment\models\PaidEmployment */

$this->title                   = Employment::t('employment', 'Paid employments');
$this->params['subtitle']      = Employment::t('employment', 'Transfer class');
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url'   => ['index']
];
$this->params['breadcrumbs'][] = $this->params['subtitle'];
?>
<div class="paid-employment-create">
    <div class="box box-success">
        <div class="box-header">
            <div class="pull-right">
                <?= Html::a('<i class="fa fa-list"></i>', ['index'],
                    [
                        'class' => 'btn btn-default btn-sm',
                        'title' => Employment::t('employment', 'List')
                    ]); ?>
                <?= Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id],
                    [
                        'class' => 'btn btn-default btn-sm',
                        'title' => Employment::t('employment', 'View')
                    ]); ?>
                <?= Html::a('<i class="fa fa-trash"></i>', ['delete', 'id' => $model->id],
                    [
                        'class' => 'btn btn-danger btn-sm',
                        'title' => Employment::t('employment', 'Delete'),
                        'data-confirm' => Employment::t('employment', 'Are you sure to delete this item?'),
                        'data-method' => 'post',
                    ]); ?>
            </div>
        </div>
        <div class="box-body">
                <?php
                $form                          = ActiveForm::begin(
                                [
                                    'id'                     => 'timetable-cansel-lessons',
                                    'enableClientValidation' => true,
                                    'validateOnChange'       => false,
                                    'validateOnBlur'         => true,
                                ]
                );
                ?>
                <div class="col-sm-12">
                    <?=
                    $form->field($model, 'date')->widget(DatePicker::className(), [
                        'type'          => DatePicker::TYPE_INPUT,
                        'options'       => [
                            'placeholder' => Employment::t('employment', 'Set date'),
                            'id'          => 'paid-employment-date',
                        ],
                        'pluginOptions' => [
                            'autoclose'      => true,
                            'format'         => 'yyyy-mm-dd',
//                            'startDate'         => date('d.m.Y'),
                            'todayHighlight' => true,
                            'todayBtn'       => true,
                        ]
                    ])
                    ?>
                </div>
                <div class="col-sm-12">
                    <?=
                    $form->field($model, 'id')->widget(DepDrop::className(), [
                        'typename'      => 'dropdownList',
                        'options'       => [
                            'placeholder' => Employment::t('employment', 'Set date first'),
                        ],
                        'pluginOptions' => [
                            'depends'     => ['paid-employment-date'],
                            'url'         => Url::to(['timetable-list']),
                            'placeholder' => false,
                        ]
                    ]);
                    ?>
                </div>
                <div class="col-sm-12">
                    <?= Html::submitButton(Employment::t('employment', 'Transfer class'), ['class' => 'btn btn-primary btn-large']) ?>
                </div>
                <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>