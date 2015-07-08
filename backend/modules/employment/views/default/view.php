<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
use backend\modules\employment\Employment;

/* @var $this yii\web\View */
/* @var $model backend\modules\employment\models\PaidEmployment */

$this->title                   = Employment::t('employment', 'Paid employments');
$this->params['subtitle']      = Employment::t('employment', 'Class information');
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url'   => ['index']
];
$this->params['breadcrumbs'][] = $this->params['subtitle'];
?>
<div class="paid-employment-view">
    <div class="box box-default">
        <div class="box-header">
            <div class="pull-right">
                <?= Html::a('<i class="fa fa-list"></i>', ['index'],
                    [
                        'class' => 'btn btn-default btn-sm',
                        'title' => Employment::t('employment', 'List')
                    ]); ?>
                <?= Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $model->id],
                    [
                        'class' => 'btn btn-success btn-sm',
                        'title' => Employment::t('employment', 'Update')
                    ]); ?>
                <?= Html::a('<i class="fa fa-trash"></i>', ['delete', 'id' => $model->id],
                    [
                        'class' => 'btn btn-danger btn-sm',
                        'title' => Employment::t('employment', 'Delete'),
                        'data-confirm' => Employment::t('employment', 'Are you sure to delete this item?'),
                        'data-method' => 'post',
                    ]); ?>
            </div>
        </div>
        <div class="box-body">
            <?=
            DetailView::widget([
                'model'      => $model,
                'attributes' => [
                    'pay.profile.fullName',
                    'timetable.group.name',
                    'date:date',
                    'timetable.start',
                    'timetable.end',
                    'pay.create_at',
                ],
            ]);
            ?>

        </div>
    </div>
</div>
