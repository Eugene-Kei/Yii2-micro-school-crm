<?php

use yii\helpers\Html;
use backend\modules\timetable\Module;
/* @var $this yii\web\View */
/* @var $model backend\modules\timetable\models\Timetable */
/* @var $groupsArray backend\modules\group\models\Group::getGroupArray() */

$this->title = Module::t('timetable-admin', 'Timetable');
$this->params['subtitle'] = Module::t('timetable-admin', 'Update item');
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->params['subtitle'];

?>
<div class="timetable-create">
    <div class="box box-success">
        <div class="box-header">
            <div class="pull-right">
                <?= Html::a('<i class="fa fa-list"></i>', ['index'],
                    [
                        'class' => 'btn btn-default btn-sm',
                        'title' => Module::t('timetable-admin', 'List')
                    ]); ?>
                <?= Html::a('<i class="fa fa-eye"></i>', ['view'],
                    [
                        'class' => 'btn btn-default btn-sm',
                        'title' => Module::t('timetable-admin', 'View')
                    ]); ?>
                <?= Html::a('<i class="fa fa-leanpub"></i>', ['publish'],
                    [
                        'class' => 'btn btn-warning btn-sm',
                        'title' => Module::t('timetable-admin', 'Publish')
                    ]); ?>
                <?= Html::a('<i class="fa fa-plus"></i>', ['create'],
                    [
                        'class' => 'btn btn-primary btn-sm',
                        'title' => Module::t('timetable-admin', 'Create')
                    ]); ?>
                <?= Html::a('<i class="fa fa-ban"></i>', ['cancel-lessons'],
                    [
                        'class' => 'btn btn-danger btn-sm',
                        'title' => Module::t('timetable-admin', 'Cancel lessons')
                    ]); ?>
                <?= Html::a('<i class="fa fa-trash"></i>', ['delete', 'id' => $user->id],
                    [
                        'class' => 'btn btn-danger btn-sm',
                        'title' => Module::t('group-admin', 'Delete'),
                        'data-confirm' => Module::t('timetable-admin', 'Are you sure to delete this item?'),
                        'data-method' => 'post',
                    ]); ?>
            </div>
        </div>
        <div class="box-body">
            <?=
            $this->render('_form', [
                'model' => $model,
                'groupsArray' => $groupsArray,
            ]);
            ?>
        </div>
    </div>
</div>
