<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use backend\modules\timetable\models\Timetable;
use yii\grid\CheckboxColumn;
use backend\modules\group\models\Group;
use backend\modules\timetable\Module;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\timetable\models\TimetableSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('timetable-admin', 'Timetable');
$this->params['subtitle'] = Module::t('timetable-admin', 'List items');
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => ['index']
];

$this->params['breadcrumbs'][] = $this->params['subtitle'];

$gridId = 'timetable-grid';

$this->registerJs(
                "jQuery(document).on('click', '#batch-delete', function (evt) {" .
                    "evt.preventDefault();" .
                    "var keys = jQuery('#" . $gridId . "').yiiGridView('getSelectedRows');" .
                    "if (keys == '') {" .
                        "alert('" . Module::t('timetable-admin', 'You need to select at least one item.') . "');" .
                    "} else {" .
                        "if (confirm('" . Module::t('timetable-admin', 'Are you sure you want to delete selected items?') . "')) {" .
                            "jQuery.ajax({" .
                                "type: 'POST'," .
                                "url: jQuery(this).attr('href')," .
                                "data: {ids: keys}" .
                            "});" .
                        "}" .
                    "}" .
                "});"
            );

?>
<div class="<?= $gridId ?>">
    <div class="box box-default">
        <div class="box-header">
            <div class="pull-right">
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
                <?= Html::a('<i class="fa fa-plus"></i>', ['create'],
                    [
                        'class' => 'btn btn-primary btn-sm',
                        'title' => Module::t('timetable-admin', 'Create')
                    ]); ?>
                <?= Html::a('<i class="fa fa-ban"></i>', ['cancel-lessons'],
                    [
                        'class' => 'btn btn-danger btn-sm',
                        'title' => Module::t('timetable-admin', 'Cancel lessons')
                    ]); ?>
                <?= Html::a('<i class="fa fa-trash"></i>', ['batch-delete'],
                    [
                        'class' => 'btn btn-danger btn-sm',
                        'id' => 'batch-delete',
                        'title' => Module::t('timetable-admin', 'Delete selected')
                    ]); ?>
            </div>
        </div>
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'id' => $gridId,
                'filterModel' => $searchModel,
                'export' => false,
                'columns' => [
                    ['class' => CheckboxColumn::classname()],
                    [
                        'attribute' => 'week_day',
                        'pageSummary' => true,
                        'class' => 'kartik\grid\EditableColumn',
                        'editableOptions' => [
                            'inputType' => 'dropDownList',
                            'type' => 'post',
                            'formOptions' => [
                                'action' => Url::toRoute('edit'),
                                'id' => 'timetable-form',
                            ],
                            'data' => Timetable::getWeekArray(),
                        ],
                        'value' => function ($model) {
                            return $model->weekArray[$model->week_day];
                        },
                        'filter' => Html::activeDropDownList(
                            $searchModel, 'week_day', Timetable::getWeekArray(),
                            ['class' => 'form-control', 'prompt' => '']
                        )
                    ],
                    [
                        'attribute' => 'start',
                        'filter' => false,
                        'class' => 'kartik\grid\EditableColumn',
                        'editableOptions' => [
                            'inputType' => 'textInput',
                            'type' => 'post',
                            'formOptions' => [
                                'action' => Url::toRoute('edit'),
                                'id' => 'timetable-form',
                            ],
                        ],
                    ],
                    [
                        'attribute' => 'end',
                        'filter' => false,
                        'class' => 'kartik\grid\EditableColumn',
                        'editableOptions' => [
                            'inputType' => 'textInput',
                            'type' => 'post',
                            'formOptions' => [
                                'action' => Url::toRoute('edit'),
                                'id' => 'timetable-form',
                            ],
                        ],
                    ],
                    [
                        'attribute' => 'group_id',
                        'class' => 'kartik\grid\EditableColumn',
                        'format' => 'html',
                        'editableOptions' => [
                            'inputType' => 'dropDownList',
                            'data' => Group::getGroupArray(),
                            'type' => 'post',
                            'formOptions' => [
                                'action' => Url::toRoute('edit'),
                                'id' => 'timetable-form',
                            ],
                        ],
                        'value' => function ($model) {
                            return Group::getGroupArray()[$model->group_id];
                        },
                        'filter' => Html::activeDropDownList(
                            $searchModel, 'group_id', Group::getGroupArray(),
                            ['class' => 'form-control', 'prompt' => '']
                        )
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'buttons' => [
                            'clone' => function ($url) {
                                return Html::a('<i class="fa fa-files-o"></i>', $url,
                                    [
                                        'title' => Module::t('timetable-admin', 'Clone row'),
                                        'class' => 'btn btn-default btn-xs'
                                    ]);
                            },
                        ],
                        'template' => '{clone} {update} {delete}',
                        'buttonOptions' => ['class' => 'btn btn-default btn-xs'],
                        'headerOptions' => ['style' => 'width:100px;'],
                        'header' => Module::t('timetable-admin', 'Actions')
                    ]
                ],
            ]); ?>
        </div>
    </div>
</div>
