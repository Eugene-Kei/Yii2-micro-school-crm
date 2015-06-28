<?php

use yii\helpers\Html,
    backend\modules\group\Module;


/* @var $this yii\web\View */
/* @var $model backend\modules\group\models\Group */

$this->title = Module::t('group-admin', 'Groups');
$this->params['subtitle'] = Module::t('group-admin', 'Create group');
$this->params['breadcrumbs'][] = [
    'label' => $this->title, 
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->params['subtitle'];
?>
<div class="group-create">
    <div class="box box-primary">
        <div class="box-header">
            <div class="pull-right">
                <?= Html::a('<i class="fa fa-list"></i>', ['index'],
                    [
                        'class' => 'btn btn-default btn-sm',
                        'title' => Module::t('group-admin', 'List')                                    ]); ?>
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
