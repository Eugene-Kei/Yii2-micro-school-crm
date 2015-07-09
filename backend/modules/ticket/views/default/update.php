<?php

use yii\helpers\Html;
use backend\modules\ticket\Ticket;


/* @var $this yii\web\View */
/* @var $model backend\modules\ticket\models\SeasonTicket */

$this->title = Ticket::t('ticket', 'Tickets');
$this->params['subtitle'] = Ticket::t('ticket', 'Update ticket');
$this->params['breadcrumbs'][] = [
    'label' => $this->title, 
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->params['subtitle'];
?>
<div class="season-ticket-create">
    <div class="box box-success">
        <div class="box-header">
            <div class="pull-right">
                <?= Html::a('<i class="fa fa-list"></i>', ['index'],
                    [
                        'class' => 'btn btn-default btn-sm',
                        'title' => Ticket::t('ticket', 'List')                                    ]); ?>
                <?= Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $user->id],
                    [
                        'class' => 'btn btn-default btn-sm',
                        'title' => Ticket::t('ticket', 'View')                                    ]); ?>
                <?= Html::a('<i class="fa fa-plus"></i>', ['create'],
                    [
                        'class' => 'btn btn-primary btn-sm',
                        'title' => Ticket::t('ticket', 'Create')                                    ]); ?>
                <?= Html::a('<i class="fa fa-trash"></i>', ['delete', 'id' => $user->id],
                    [
                        'class' => 'btn btn-danger btn-sm',
                        'title' => Ticket::t('ticket', 'Delete'),
                        'data-confirm' => Ticket::t('ticket', 'Are you sure to delete this item?'),
                        'data-method' => 'post',
                    ]); ?>
            </div>
        </div>
        <div class="box-body">
            <?= $this->render('_form', [
                'model' => $model,
            ]);  ?>
        </div>
    </div>
</div>
