<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\CheckboxColumn;
use kartik\daterange\DateRangePicker;
use backend\modules\user\Module;

/* @var $this yii\web\View */
/* @var $statusArray common\models\User::getStatusArrayStatic() */
/* @var $searchModel backend\modules\user\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('user-admin', 'Users');
$this->params['subtitle'] = Module::t('user-admin', 'List users');
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->params['subtitle'];

$gridId = 'user-grid';

$this->registerJs(
                "jQuery(document).on('click', '#batch-delete', function (evt) {" .
                    "evt.preventDefault();" .
                    "var keys = jQuery('#" . $gridId . "').yiiGridView('getSelectedRows');" .
                    "if (keys == '') {" .
                        "alert('" . Module::t('user-admin', 'You need to select at least one item.') . "');" .
                    "} else {" .
                        "if (confirm('" . Module::t('user-admin', 'Are you sure you want to delete selected items?') . "')) {" .
                            "jQuery.ajax({" .
                                "type: 'POST'," .
                                "url: jQuery(this).attr('href')," .
                                "data: {ids: keys}" .
                            "});" .
                        "}" .
                    "}" .
                "});"
            );

?>
<div class="<?= $gridId ?>">
    <div class="box box-default">
        <div class="box-header">
            <div class="pull-right">
                <?= Html::a('<i class="fa fa-money text-danger"></i>', ['debtor'],
                    [
                        'class' => 'btn btn-default btn-sm',
                        'title' => Module::t('user-admin', 'List debtors')
                    ]); ?>
                <?= Html::a('<i class="fa fa-plus"></i>', ['create'],
                    [
                        'class' => 'btn btn-primary btn-sm',
                        'title' => Module::t('user-admin', 'Create')
                    ]); ?>
                <?= Html::a('<i class="fa fa-trash"></i>', ['batch-delete'],
                    [
                        'class' => 'btn btn-danger btn-sm',
                        'id' => 'batch-delete',
                        'title' => Module::t('user-admin', 'Delete selected')
                    ]); ?>
            </div>
        </div>
        <div class="box-body">

            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'id' => $gridId,
                'options' => ['class' => 'table-responsive'],
                'filterModel' => $searchModel,
                'rowOptions' => function ($model) {
                    $array = [];
                    if ($model->profile->balance < 0) {
                        $array['class'] = 'danger';

                        return $array;
                    }
                },
                'columns' => [
                    ['class' => CheckboxColumn::classname()],
                    [
                        'label' => false,
                        'format' => 'image',
                        'value' => 'profile.fullAvatarUrl',
                        'contentOptions' => ['class' => 'grid-avatar'],
                    ],
                    'profile.surname',
                    'profile.name',
                    'profile.middle_name',
//                    'phone',
//                    'email:email',
                    [
                        'attribute' => 'status',
                        'value' => function ($data) {
                            return $data->statusArray[$data->status];
                        },
                        'filter' => Html::activeDropDownList(
                            $searchModel, 'status', $statusArray, ['class' => 'form-control', 'prompt' => '']
                        )
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => 'datetime',
                        'filterOptions' => [
                            'class' => 'date-range-grid'
                        ],
                        'filter' => DateRangePicker::widget(
                            [
                                'model' => $searchModel,
                                'attribute' => 'created_at',
                                'convertFormat' => true,
                                'presetDropdown' => true,
                                'options' => [
                                    'class' => 'form-control',
                                ],
                                'pluginOptions' => [
                                    'format' => 'Y-m-d H:i:s',
                                    'dateLimit' => ['months' => 6],
                                    'opens' => 'left'
                                ],
                            ]
                        )
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'buttonOptions' => ['class' => 'btn btn-default btn-xs'],
                        'headerOptions' => ['style' => 'width:155px;'],
                        'header' => Module::t('user-admin', 'Actions'),
                        'template' => '{pay} {history} {view} {update} {delete}',
                        'buttons' => [
                            'pay' => function ($url, $model) {
                                $customurl = Yii::$app->getUrlManager()->createUrl([
                                    '/pay/default/create/',
                                    'user_id' => $model->id
                                ]);

                                return Yii::$app->user->can('/pay/*') ?
                                    \yii\helpers\Html::a(
                                        '<span class="fa fa-money"></span>', $customurl, [
                                            'title' => Module::t('user-admin', 'Pay'),
                                            'data-pjax' => '0',
                                            'class' => 'btn btn-default btn-xs'
                                        ]
                                    )
                                    : '';
                            },
                            'history' => function ($url, $model) {
                                $customurl = Yii::$app->getUrlManager()->createUrl([
                                    '/pay/default/index/',
                                    'PaySearch' => ['user_id' => $model->id]
                                ]);

                                return Yii::$app->user->can('/pay/*') ?
                                    \yii\helpers\Html::a(
                                        '<span class="fa fa-history"></span>', $customurl, [
                                            'title' => Module::t('user-admin', 'Payment history'),
                                            'data-pjax' => '0',
                                            'class' => 'btn btn-default btn-xs'
                                        ]
                                    )
                                    : '';
                            },
                        ],
                    ],
                ],
            ]); ?>

        </div>
    </div>
</div>
