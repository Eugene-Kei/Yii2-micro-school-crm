<?php

use kartik\grid\GridView;
use kartik\daterange\DateRangePicker;
use yii\grid\CheckboxColumn;
use yii\helpers\Html;
use backend\modules\pay\Pay;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\pay\models\PaySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $ticketsArray backend\modules\ticket\models\SeasonTicket (array(id=>title)) */
$this->title                   = Pay::t('pay-admin', 'Pays');
$this->params['subtitle']      = Pay::t('pay-admin', 'List pays');
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url'   => ['index']
];
$this->params['breadcrumbs'][] = $this->params['subtitle'];

$gridId = 'pay-grid';

$this->registerJs(
                "jQuery(document).on('click', '#batch-delete', function (evt) {" .
                    "evt.preventDefault();" .
                    "var keys = jQuery('#" . $gridId . "').yiiGridView('getSelectedRows');" .
                    "if (keys == '') {" .
                        "alert('" . Pay::t('pay-admin', 'You need to select at least one item.') . "');" .
                    "} else {" .
                        "if (confirm('" . Pay::t('pay-admin', 'Are you sure you want to delete selected items?') . "')) {" .
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
                <?= Html::a('<i class="fa fa-plus"></i>', ['create'],
                    [
                        'class' => 'btn btn-primary btn-sm',
                        'title' => Pay::t('pay-admin', 'Create')
                    ]); ?>
                <?= Html::a('<i class="fa fa-trash"></i>', ['batch-delete'],
                    [
                        'class' => 'btn btn-danger btn-sm',
                        'id' => 'batch-delete',
                        'title' => Pay::t('pay-admin', 'Delete selected')
                    ]); ?>
            </div>
        </div>
        <div class="box-body">
            <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'id'           => $gridId,
                'export'       => false,
                'filterModel'  => $searchModel,
                'columns'      => [
                    ['class' => CheckboxColumn::classname()],
                    [
                        'attribute' => 'create_at',
                        'format' => 'datetime',
                        'filterOptions' => [
                            'class' => 'date-range-grid'
                        ],
                        'filter' => DateRangePicker::widget(
                            [
                                'model' => $searchModel,
                                'attribute' => 'create_at',
                                'convertFormat' => true,
                                'presetDropdown' => true,
                                'options' => [
                                    'class' => 'form-control',
                                ],
                                'pluginOptions' => [
                                    'format' => 'Y-m-d H:i:s',
                                    'dateLimit' => ['months' => 6],
                                    'opens' => 'right'
                                ],
                            ]
                        )
                    ],
                    [
                        'attribute' => 'userFullName',
                        'value'     => function ($model) {
                            return $model->userFullName;
                        }
                    ],
                    [
                        'attribute' => 'ticket_id',
                        'value'     => function ($model) {
                            return $model->ticket->title;
                        },
                        'filter' => Html::activeDropDownList($searchModel, 'ticket_id', $ticketsArray, ['class' => 'form-control', 'prompt' => ''])
                    ],
                    'current_cost',
                    'cash',
                    'bonus_cash',
                    'comment',
                    [
                        'template' => '{view} {delete}',
                        'class'    => 'yii\grid\ActionColumn',
                        'buttonOptions' => ['class' => 'btn btn-default btn-xs'],
                        'headerOptions' => ['style' => 'width:55px;'],
                        'header' => Pay::t('pay-admin', 'Actions')
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
</div>
