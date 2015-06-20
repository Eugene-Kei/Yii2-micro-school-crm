<?php

use eugenekei\news\Module;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model eugenekei\news\models\News */

$this->title = Module::t('module', 'News');
$this->params['subtitle'] = Module::t('module', 'Update News');
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->params['subtitle'];
?>
<div class="news-create">
    <div class="box box-success">
        <div class="box-header">
            <div class="pull-right">
                <?= Html::a('<i class="glyphicon glyphicon-list"></i>', ['index'],
                    [
                        'class' => 'btn btn-default btn-sm',
                        'title' => Module::t('module', 'List')
                    ]); ?>
                <?= Html::a('<i class="glyphicon glyphicon-eye-open"></i>', ['view', 'id' => $model->id],
                    [
                        'class' => 'btn btn-default btn-sm',
                        'title' => Module::t('module', 'View')
                    ]); ?>
                <?= Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                    [
                        'class' => 'btn btn-primary btn-sm',
                        'title' => Module::t('module', 'Create')
                    ]); ?>
                <?= Html::a('<i class="glyphicon glyphicon-trash"></i>', ['delete', 'id' => $model->id],
                    [
                        'class' => 'btn btn-danger btn-sm',
                        'title' => Module::t('module', 'Delete'),
                        'data-confirm' => Module::t('module', 'Are you sure to delete this item?'),
                        'data-method' => 'post',
                    ]); ?>
            </div>
        </div>
        <div class="box-body">
            <?= $this->render('_form', [
                'model' => $model,
                'box' => $box,
            ]); ?>
        </div>
    </div>
</div>