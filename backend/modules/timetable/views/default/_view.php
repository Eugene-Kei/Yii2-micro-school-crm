<?php

use backend\modules\timetable\widgets\GroupGridView as GridView;
use backend\modules\group\models\Group;


          echo  GridView::widget([
                'id' => 'show-timetable',
                'dataProvider' => $dataProvider,
                'layout' => '{items}',
                'tableOptions' => ['class' => 'table table-bordered text-center'],
                'headerRowOptions' => ['class' => 'active'],
                'mergeColumns' => [
                    'week_day'
                ],
                'columns' => [
                    [
                        'attribute' => 'week_day',
                        'options' => ['class' => 'col-xs-3'],
                        'contentOptions' => [
                            'style' => 'vertical-align: middle;'
                            ],
                        'value' => function($model) {
                    return $model->weekArray[$model->week_day];
                },
                    ],
                    [
                        'attribute' => 'start',
                        'options' => [
                            'class' => 'col-xs-2',
                        ],
                        'contentOptions' => [
                            'style' => 'vertical-align: middle;'
                            ],
                    ],
                    [
                        'attribute' => 'end',
                        'options' => [
                            'class' => 'col-xs-2',
                        ],
                        'contentOptions' => [
                            'style' => 'vertical-align: middle;'
                            ],
                    ],
                    [
                        'attribute' => 'group_id',
                        'options' => [
                            'class' => 'col-xs-5',
                        ],
                        'value' => function($model) {
                    return Group::getGroupArray()[$model->group_id];
                },
                    ]
                ],
            ]);
