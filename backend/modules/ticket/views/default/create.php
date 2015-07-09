<?php

use backend\modules\ticket\Ticket;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\ticket\models\SeasonTicket */

$this->title = Ticket::t('ticket', 'Tickets');
$this->params['subtitle'] = Ticket::t('ticket', 'Create ticket');
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->params['subtitle'];
?>
<div class="season-ticket-create">
    <div class="box box-primary">
        <div class="box-header">
            <div class="pull-right">
                <?= Html::a('<i class="fa fa-list"></i>', ['index'],
                    [
                        'class' => 'btn btn-default btn-sm',
                        'title' => Ticket::t('ticket', 'List')
                    ]); ?>
            </div>
        </div>
        <div class="box-body">
            <?= $this->render('_form', [
                'model' => $model,
            ]); ?>
        </div>
    </div>
</div>
