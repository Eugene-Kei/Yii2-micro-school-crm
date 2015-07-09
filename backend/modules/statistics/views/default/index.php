<?php

use dosamigos\chartjs\ChartJs;
use yii\helpers\ArrayHelper;
use backend\modules\statistics\Statistics;

$this->title                   = Statistics::t('statistics', 'Statistics');
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url'   => ['index']
];

?>

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title"><?= Statistics::t('statistics', 'Statistics of payments for the year') ?></h3>
        <br />
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <?php
                    echo ChartJs::widget([
                        'type'          => 'Bar',
                        'options'       => [
                            'class' => 'chartjs'
                        ],
                        'clientOptions' => [
//                        'responsive'  => true
                            'scaleBeginAtZero'         => true,
                            'scaleShowGridLines'       => true,
                            'scaleGridLineColor'       => true,
                            'scaleShowHorizontalLines' => true,
                            'scaleShowVerticalLines'   => true,
                            'barShowStroke'            => true,
                            'barStrokeWidth'           => 2,
                            'barValueSpacing'          => 5,
                            'barDatasetSpacing'        => 1,
                            'scaleGridLineColor'       => "rgba(0,0,0,0.3)",
                            'scaleGridLineWidth'       => 1,
                        ],
                        'data'          => [
                            'labels' => ArrayHelper::getColumn($payStat, function($element) {
                                        return date('F', mktime(0, 0, 0, $element['month']));
                                    }),
                            'datasets'      => [
                                [
                                    'label'            => "Количество платежей",
                                    'fillColor'        => "rgba(220,0,0,0.5)",
                                    'strokeColor'      => "rgba(220,0,0,1)",
                                    'pointColor'       => "rgba(220,0,0,1)",
                                    'pointStrokeColor' => "#fff",
                                    'data'             => ArrayHelper::getColumn($payStat, 'count')
                                ],
                                [
                                    'label'            => "Сумма платежей",
                                    'fillColor'        => "rgba(0,220,0,0.5)",
                                    'strokeColor'      => "rgba(0,220,0,1)",
                                    'pointColor'       => "rgba(0,220,0,1)",
                                    'pointStrokeColor' => "#fff",
                                    'data'             => ArrayHelper::getColumn($payStat, 'sum_cash')
                                ],
                                [
                                    'label'            => "Сумма платежей бонусами",
                                    'fillColor'        => "rgba(0,0,205,0.5)",
                                    'strokeColor'      => "rgba(0,0,205,1)",
                                    'pointColor'       => "rgba(0,0,205,1)",
                                    'pointStrokeColor' => "#fff",
                                    'data'             => ArrayHelper::getColumn($payStat, 'sum_bonus_cash')
                                ]
                            ]
                        ]
                    ]);
                    ?>

                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="row">
                        <div class="col-xs-1">
                            <div class="chart-info" style="background-color: rgba(220,0,0,1)"></div>
                        </div>
                        <div class="col-xs-11">
                            - <?= Statistics::t('statistics', 'Number of payments') ?>.
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-1">
                            <div class="chart-info" style="background-color: rgba(0,220,0,1)"></div>
                        </div>
                        <div class="col-xs-11">
                            - <?= Statistics::t('statistics', 'The amount of cash payments') ?>.
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-1">
                            <div class="chart-info" style="background-color: rgba(0,0,205,1)"></div>
                        </div>
                        <div class="col-xs-11">
                            - <?= Statistics::t('statistics', 'The amount of bonus payments') ?>.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title"><?= Statistics::t('statistics', 'Statistics of payments for 30 days') ?></h3>
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <?php
                    echo ChartJs::widget([
                        'type'          => 'Bar',
                        'options'       => [
                            'class' => 'chartjs'
                        ],
                        'clientOptions' => [
//                        'responsive'  => true
                            'scaleBeginAtZero'         => true,
                            'scaleShowGridLines'       => true,
                            'scaleGridLineColor'       => true,
                            'scaleShowHorizontalLines' => true,
                            'scaleShowVerticalLines'   => true,
                            'barShowStroke'            => true,
                            'barStrokeWidth'           => 2,
                            'barValueSpacing'          => 5,
                            'barDatasetSpacing'        => 1,
                            'scaleGridLineColor'       => "rgba(0,0,0,0.3)",
                            'scaleGridLineWidth'       => 1,
                        ],
                        'data'          => [
                            'labels'   => ArrayHelper::getColumn($payMonthStat, 'date'),
                            'datasets' => [
                                [
                                    'label'            => "Количество платежей",
                                    'fillColor'        => "rgba(220,0,0,0.5)",
                                    'strokeColor'      => "rgba(220,0,0,1)",
                                    'pointColor'       => "rgba(220,0,0,1)",
                                    'pointStrokeColor' => "#fff",
                                    'data'             => ArrayHelper::getColumn($payMonthStat, 'count')
                                ],
                                [
                                    'label'            => "Сумма платежей",
                                    'fillColor'        => "rgba(0,220,0,0.5)",
                                    'strokeColor'      => "rgba(0,220,0,1)",
                                    'pointColor'       => "rgba(0,220,0,1)",
                                    'pointStrokeColor' => "#fff",
                                    'data'             => ArrayHelper::getColumn($payMonthStat, 'sum_cash')
                                ],
                                [
                                    'label'            => "Сумма платежей бонусами",
                                    'fillColor'        => "rgba(0,0,205,0.5)",
                                    'strokeColor'      => "rgba(0,0,205,1)",
                                    'pointColor'       => "rgba(0,0,205,1)",
                                    'pointStrokeColor' => "#fff",
                                    'data'             => ArrayHelper::getColumn($payMonthStat, 'sum_bonus_cash')
                                ]
                            ]
                        ]
                    ]);
                    ?>

                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="row">
                        <div class="col-xs-1">
                            <div class="chart-info" style="background-color: rgba(220,0,0,1)"></div>
                        </div>
                        <div class="col-xs-11">
                            - <?= Statistics::t('statistics', 'Number of payments') ?>.
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-1">
                            <div class="chart-info" style="background-color: rgba(0,220,0,1)"></div>
                        </div>
                        <div class="col-xs-11">
                            - <?= Statistics::t('statistics', 'The amount of cash payments') ?>.
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-1">
                            <div class="chart-info" style="background-color: rgba(0,0,205,1)"></div>
                        </div>
                        <div class="col-xs-11">
                            - <?= Statistics::t('statistics', 'The amount of bonus payments') ?>.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>







