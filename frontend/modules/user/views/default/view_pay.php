<?php

use yii\widgets\DetailView;
use yii\grid\GridView;

$this->title                   = 'Просмотр платежа';
$this->params['breadcrumbs'][] = ['label' => 'Личный кабинет', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">


    <?=
    DetailView::widget([
        'model'      => $model,
        'attributes' => [
//                        'id',
                    [
                        'attribute' => 'ticket_id',
                        'value'     => $model->getTicket()->one()->title,
                    ],
                    'current_cost',
                    'cash',
                    'bonus_cash',
                    'create_at'
                ],
    ])
    ?>
    <hr />
            <div class="box-header">
                <h4>Оплаченные занятия</h4>
            </div>
            <?=
            GridView::widget([
                'dataProvider' => $paidEmployment,
                'id'           => 'paid-employment',
                'columns'      => [
                    [
                        'attribute' => 'date',
                        'value'     => function ($model) {
                            return  Yii::$app->formatter->asTime(strtotime($model->date),'php:d.m.Y')
                                    . ' ('.Yii::$app->formatter->asTime(strtotime($model->date), 'php:l').')';
                        }
                    ],
                    'timetable.start',
                    'timetable.end',
                    'timetable.group.name'
                ],
            ]);
            ?>

</div>
