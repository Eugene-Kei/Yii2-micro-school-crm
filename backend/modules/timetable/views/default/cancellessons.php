<?php

use backend\modules\timetable\Module;
use backend\modules\timetable\widgets\DepDrop;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = Module::t('timetable-admin', 'Timetable');
$this->params['subtitle'] = Module::t('timetable-admin', 'Cancel lessons');
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => ['index']
];

$this->params['breadcrumbs'][] = $this->params['subtitle'];

$gridId = 'timetable-cancel';
?>
<div class="<?= $gridId ?>">
    <div class="box box-primary">
        <div class="box-header">
            <div class="pull-right">
                <?= Html::a('<i class="fa fa-list"></i>', ['index'],
                    [
                        'class' => 'btn btn-default btn-sm',
                        'title' => Module::t('timetable-admin', 'List')
                    ]); ?>
                <?= Html::a('<i class="fa fa-eye"></i>', ['view'],
                    [
                        'class' => 'btn btn-default btn-sm',
                        'title' => Module::t('timetable-admin', 'View')
                    ]); ?>
                <?= Html::a('<i class="fa fa-leanpub"></i>', ['publish'],
                    [
                        'class' => 'btn btn-warning btn-sm',
                        'title' => Module::t('timetable-admin', 'Publish')
                    ]); ?>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <?php
                $form = ActiveForm::begin(
                    [
                        'id' => 'timetable-cansel-lessons',
                        'enableClientValidation' => true,
                    ]
                );
                ?>
                <div class="col-sm-12">
                    <?=
                    $form->field($model, 'date')->widget(DatePicker::className(), [
                        'name' => '',
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => [
                            'placeholder' => Module::t('timetable-admin', 'Set the date'),
                        ],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd.mm.yyyy',
                            'todayHighlight' => true,
                            'todayBtn' => true,
                        ]
                    ])
                    ?>
                </div>
                <div class="col-sm-12">
                    <?=
                    $form->field($model, 'ids')->widget(DepDrop::className(), [
                        'typename' => 'listBox',
                        'options' => [
                            'placeholder' => Module::t('timetable-admin', 'Set the date first'),
                            'multiple' => true,
                            'size' => 7,
                        ],
                        'pluginOptions' => [
                            'depends' => ['timetablecancel-date'],
                            'url' => Url::to(['cancel-lessons']),
                            'placeholder' => false,
                        ]
                    ]);
                    ?>
                </div>
                <div class="col-sm-12">
                    <?= Html::submitButton(Module::t('timetable-admin', 'Cancel lessons'),
                        ['class' => 'btn btn-primary btn-large']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
