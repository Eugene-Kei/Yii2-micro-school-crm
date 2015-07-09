<?php

use yii\helpers\Html;
    use backend\modules\user\Module;


/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $profile common\models\Profile */

$this->title = $profile->fullName;
$this->params['subtitle'] = Module::t('user-admin', 'Update user');
$this->params['breadcrumbs'][] = [
'label' => Module::t('user-admin', 'Users'),
'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->params['subtitle'];
?>
<div class="user-update">
    <div class="box box-success">
        <div class="box-header">
            <div class="pull-right">
                <?= Html::a('<i class="fa fa-list"></i>', ['index'],
                                    [
                                        'class' => 'btn btn-default btn-sm',
                                        'title' => Module::t('user-admin', 'List')
                                    ]); ?>
                <?= Html::a('<i class="fa fa-money text-danger"></i>', ['debtor'],
                                    [
                                        'class' => 'btn btn-default btn-sm',
                                        'title' => Module::t('user-admin', 'List debtors')
                                    ]); ?>
                <?= Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $user->id],
                                    [
                                        'class' => 'btn btn-default btn-sm',
                                        'title' => Module::t('user-admin', 'View')
                                    ]); ?>
                <?= Html::a('<i class="fa fa-plus"></i>', ['create'],
                                    [
                                        'class' => 'btn btn-primary btn-sm',
                                        'title' => Module::t('user-admin', 'Create')
                                    ]); ?>
                <?= Html::a('<i class="fa fa-trash"></i>', ['delete', 'id' => $user->id],
                                    [
                                        'class' => 'btn btn-danger btn-sm',
                                        'title' => Module::t('user-admin', 'Delete'),
                                        'data-confirm' => Module::t('user-admin', 'Are you sure to delete this item?'),
                                        'data-method' => 'post',
                                    ]); ?>
            </div>
        </div>
        <div class="box-body">
            <?= $this->render('_form', compact('user', 'profile')); ?>
        </div>
    </div>
</div>