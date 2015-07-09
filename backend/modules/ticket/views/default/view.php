<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\modules\ticket\Ticket;

/* @var $this yii\web\View */
/* @var $model backend\modules\ticket\models\SeasonTicket */

$this->title = Ticket::t('ticket', 'Tickets');
$this->params['subtitle'] = Ticket::t('ticket', 'View ticket');
$this->params['breadcrumbs'][] = [
    'label' => $this->title, 
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->params['subtitle'];
?>
<div class="season-ticket-view">
    <div class="box box-default">
        <div class="box-header">
            <div class="pull-right">
                <?= Html::a('<i class="fa fa-list"></i>', ['index'],
                    [
                        'class' => 'btn btn-default btn-sm',
                        'title' => Ticket::t('ticket', 'List')
                    ]); ?>
                <?= Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $model->id],
                    [
                        'class' => 'btn btn-success btn-sm',
                        'title' => Ticket::t('ticket', 'Update')
                    ]); ?>
                <?= Html::a('<i class="fa fa-plus"></i>', ['create'],
                    [
                        'class' => 'btn btn-primary btn-sm',
                        'title' => Ticket::t('ticket', 'Create')
                    ]); ?>
                <?= Html::a('<i class="fa fa-trash"></i>', ['delete', 'id' => $model->id],
                    [
                        'class' => 'btn btn-danger btn-sm',
                        'title' => Ticket::t('ticket', 'Delete'),
                        'data-confirm' => Ticket::t('ticket', 'Are you sure to delete this item?'),
                        'data-method' => 'post',
                    ]); ?>
            </div>
        </div>
        <div class="box-body">
            <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                        'id',
            'title',
            'description',
            'cost',
            'amount',
                [
                  'attribute' => 'limit_format',
                    'value' => $model->getLimitFormatArray()[$model->limit_format],
                ],
            'limit_value',
            ],
            ]);
            ?>

        </div>
    </div>
</div>

