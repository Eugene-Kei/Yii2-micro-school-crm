<?php

use backend\modules\user\Module;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->profile->fullName;
$this->params['subtitle'] = Module::t('user-admin', 'View user');
$this->params['breadcrumbs'][] = [
    'label' => Module::t('user-admin', 'Users'),
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">
    <div class="box box-default">
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
                <?= Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $model->id],
                    [
                        'class' => 'btn btn-success btn-sm',
                        'title' => Module::t('user-admin', 'Update')
                    ]); ?>
                <?= Html::a('<i class="fa fa-plus"></i>', ['create'],
                    [
                        'class' => 'btn btn-primary btn-sm',
                        'title' => Module::t('user-admin', 'Create')
                    ]); ?>
                <?= Html::a('<i class="fa fa-trash"></i>', ['delete', 'id' => $model->id],
                    [
                        'class' => 'btn btn-danger btn-sm',
                        'title' => Module::t('user-admin', 'Delete'),
                        'data-confirm' => Module::t('user-admin', 'Are you sure to delete this item?'),
                        'data-method' => 'post',
                    ]); ?>
            </div>
        </div>
        <div class="box-body">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'profile.FullAvatarUrl:image:' . Yii::t('app', 'Avatar'),
                    'id',
                    'profile.surname',
                    'profile.name',
                    'profile.middle_name',
                    [
                        'attribute' => 'profile.gender',
                        'value' => $model->profile->genderArray[$model->profile->gender]
                    ],
                    'profile.birthday:date',
                    'phone',
                    'email:email',
                    [
                        'attribute' => 'status',
                        'value' => $model->getStatusArray()[$model->status]
                    ],
                    'profile.balance',
                    'profile.bonus_balance',
                    [
                        'attribute' => 'profile.user_affiliate_id',
                        'value' => Html::a($model->profile->profileAffiliate->fullName,
                            ['', 'id' => $model->profile->user_affiliate_id]),
                        'format' => 'html'
                    ],
                    'created_at:datetime',
                    'updated_at:datetime',
                ],
            ]);
            ?>
        </div>
    </div>
</div>
