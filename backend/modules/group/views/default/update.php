<?php

use yii\helpers\Html;
use backend\modules\group\Module;


/* @var $this yii\web\View */
/* @var $model backend\modules\group\models\Group */

$this->title = Module::t('group-admin', 'Groups');
$this->params['subtitle'] = Module::t('group-admin', 'Update group');
$this->params['breadcrumbs'][] = [
    'label' => $this->title, 
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->params['subtitle'];
?>
<div class="group-update">
    <div class="box box-success">
        <div class="box-header">
            <div class="pull-right">
                <?= Html::a('<i class="fa fa-list"></i>', ['index'],
                    [
                        'class' => 'btn btn-default btn-sm',
                        'title' => Module::t('group-admin', 'List')]); ?>
                <?= Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $user->id],
                    [
                        'class' => 'btn btn-default btn-sm',
                        'title' => Module::t('group-admin', 'View')]); ?>
                <?= Html::a('<i class="fa fa-plus"></i>', ['create'],
                    [
                        'class' => 'btn btn-primary btn-sm',
                        'title' => Module::t('group-admin', 'Create')]); ?>
                <?= Html::a('<i class="fa fa-trash"></i>', ['delete', 'id' => $user->id],
                    [
                        'class' => 'btn btn-danger btn-sm',
                        'title' => Module::t('group-admin', 'Delete'),
                        'data-confirm' => Module::t('group-admin', 'Are you sure to delete this item?'),
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
