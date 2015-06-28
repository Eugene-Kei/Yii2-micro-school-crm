<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\modules\group\Module;

/* @var $this yii\web\View */
/* @var $model backend\modules\group\models\Group */

$this->title = 'Группы';
$this->params['subtitle'] = 'Просмотр группы';
$this->params['breadcrumbs'][] = [
    'label' => $this->title, 
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->params['subtitle'];
?>
<div class="group-view">
    <div class="box box-default">
        <div class="box-header">
            <div class="pull-right">
                <?= Html::a('<i class="fa fa-list"></i>', ['index'],
                    [
                        'class' => 'btn btn-default btn-sm',
                        'title' => Module::t('group-admin', 'List')
                    ]); ?>
                <?= Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $model->id],
                    [
                        'class' => 'btn btn-success btn-sm',
                        'title' => Module::t('group-admin', 'Update')
                    ]); ?>
                <?= Html::a('<i class="fa fa-plus"></i>', ['create'],
                    [
                        'class' => 'btn btn-primary btn-sm',
                        'title' => Module::t('group-admin', 'Create')
                    ]); ?>
                <?= Html::a('<i class="fa fa-trash"></i>', ['delete', 'id' => $model->id],
                    [
                        'class' => 'btn btn-danger btn-sm',
                        'title' => Module::t('group-admin', 'Delete'),
                        'data-confirm' => Module::t('group-admin', 'Are you sure to delete this item?'),
                        'data-method' => 'post',
                    ]); ?>
            </div>
        </div>
        <div class="box-body">
            <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                        'id',
            'name',
            'description:html',
                    [
                        'attribute' => 'status',
                        'value'=> $model->getStatusArray()[$model->status]
                    ],
            ],
            ]);
            ?>

        </div>
    </div>
</div>

