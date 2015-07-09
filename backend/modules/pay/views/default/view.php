<?php

use backend\modules\pay\Pay;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\pay\models\Pay */

$this->title = Pay::t('pay-admin', 'Pays');
$this->params['subtitle'] = Pay::t('pay-admin', 'Payment info');
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->params['subtitle'];
?>
<div class="pay-view">
    <div class="box box-default">
        <div class="box-header">
            <div class="pull-right">
                <?= Html::a('<i class="fa fa-list"></i>', ['index'],
                    [
                        'class' => 'btn btn-default btn-sm',
                        'title' => Pay::t('pay-admin', 'List')
                    ]); ?>
                <?= Html::a('<i class="fa fa-plus"></i>', ['create'],
                    [
                        'class' => 'btn btn-primary btn-sm',
                        'title' => Pay::t('pay-admin', 'Create')
                    ]); ?>
                <?= Html::a('<i class="fa fa-trash"></i>', ['delete', 'id' => $model->id],
                    [
                        'class' => 'btn btn-danger btn-sm',
                        'title' => Pay::t('pay-admin', 'Delete'),
                        'data-confirm' => Pay::t('pay-admin', 'Are you sure to delete this item?'),
                        'data-method' => 'post',
                    ]); ?>
            </div>
        </div>
        <div class="box-body">
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
//                        'id',
                    [
                        'attribute' => 'user_id',
                        'value' => $model->profile->fullName,
                    ],
                    [
                        'attribute' => 'ticket_id',
                        'value' => $model->ticket->title,
                    ],
                    'current_cost',
                    'cash',
                    'bonus_cash',
                    'comment',
                    'create_at:datetime',
                ],
            ]);
            ?>
            <hr/>
            <div class="box-header">
                <h4><?=Pay::t('pay-admin', 'Paid employments')?></h4>
            </div>
            <?=
            GridView::widget([
                'dataProvider' => $paidEmployment,
                'id' => 'paid-employment',
                'columns' => [
                    [
                        'attribute' => 'date',
                        'value' => function ($model) {
                            return Yii::$app->formatter->asTime(strtotime($model->date), 'php:d.m.Y')
                            . ' (' . Yii::$app->formatter->asTime(strtotime($model->date), 'php:l') . ')';
                        }
                    ],
                    'timetable.start',
                    'timetable.end',
                    'timetable.group.name',
                    [
                        'class' => ActionColumn::className(),
                        'template' => '{update_employment}',
                        'header' => Pay::t('pay-admin', 'Actions'),
                        'buttons' => [
                            'update_employment' => function ($url, $model) {
                                $customurl = Yii::$app->getUrlManager()->createUrl([
                                    '/employment/default/update/',
                                    'id' => $model->id
                                ]);

                                return \yii\helpers\Html::a(
                                    '<span class="glyphicon glyphicon-pencil"></span>', $customurl, [
                                        'title' => Pay::t('pay-admin', 'Transfer class'),
                                        'class' => 'btn btn-default btn-xs',
                                        'data-pjax' => '0'
                                    ]
                                );
                            }
                        ],
                    ]
//                    'group.description',
                ],
            ]);
            ?>

        </div>
    </div>
</div>

