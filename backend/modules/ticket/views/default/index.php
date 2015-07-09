<?php

use backend\modules\ticket\models\SeasonTicket;
use backend\modules\ticket\Ticket;
use yii\grid\GridView;
use yii\grid\CheckboxColumn;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\ticket\models\TicketSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Ticket::t('ticket', 'Tickets');
$this->params['subtitle'] = Ticket::t('ticket', 'List tickets');
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->params['subtitle'];

$gridId = 'season-ticket-grid';

$this->registerJs(
                "jQuery(document).on('click', '#batch-delete', function (evt) {" .
                    "evt.preventDefault();" .
                    "var keys = jQuery('#" . $gridId . "').yiiGridView('getSelectedRows');" .
                    "if (keys == '') {" .
                        "alert('" . Ticket::t('ticket', 'You need to select at least one item.') . "');" .
                    "} else {" .
                        "if (confirm('" . Ticket::t('ticket', 'Are you sure you want to delete selected items?') . "')) {" .
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
                        'title' => Ticket::t('ticket', 'Create')
                    ]); ?>
                <?= Html::a('<i class="fa fa-trash"></i>', ['batch-delete'],
                    [
                        'class' => 'btn btn-danger btn-sm',
                        'id' => 'batch-delete',
                        'title' => Ticket::t('ticket', 'Delete selected')
                    ]); ?>
            </div>
        </div>
        <div class="box-body">
            <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'id' => $gridId,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => CheckboxColumn::classname()],
                    'title',
                    'description',
                    'cost',
                    'amount',
                    'limit_value',
                    [
                        'attribute' => 'limit_format',
                        'value' => function ($model) {
                            return SeasonTicket::getLimitFormatArray()[$model->limit_format];
                        },
                        'filter' => Html::activeDropDownList(
                            $searchModel, 'limit_format', SeasonTicket::getLimitFormatArray(),
                            ['class' => 'form-control', 'prompt' => '']
                        )
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'buttonOptions' => ['class' => 'btn btn-default btn-xs'],
                        'headerOptions' => ['style' => 'width:100px;'],
                        'header' => Ticket::t('ticket', 'Actions')
                    ]
                ],
            ]);
            ?>

        </div>
    </div>
</div>
