<?php

/**
 * Users list view.
 *
 * @var \yii\base\View $this View
 * @var \yii\data\ActiveDataProvider $dataProvider Data provider
 * @var \backend\modules\user\\models\UserSearch $searchModel Search model
 * @var array $roleArray Roles array
 * @var array $statusArray Statuses array
 */
use yii\grid\GridView;
use backend\modules\user\Module;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use backend\modules\pay\Pay;

$module = new Module('user');

$this->title = Pay::t('pay-admin', 'Pays');
$this->params['subtitle'] = Pay::t('pay-admin', 'Choose a user');
$this->params['breadcrumbs'] = [
    $this->title
];
$gridId = 'users-grid';
$gridConfig = [
    'id' => $gridId,
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'rowOptions' => function ($model) {
        $array = [];
        if ($model->profile->balance < 0) {
            $array['class'] = 'danger';

            return $array;
        }
    },
    'columns' => [
        [
            'label' => false,
            'format' => 'image',
            'value' => 'profile.fullAvatarUrl',
            'contentOptions' => ['class' => 'grid-avatar'],
        ],
        'profile.surname',
        'profile.name',
        'profile.middle_name',
        [
            'class' => ActionColumn::className(),
            'header' => Module::t('user-admin', 'Actions'),
            'headerOptions' => ['style' => 'width:55px;'],
            'template' => '{pay}',
            'buttons' => [
                'pay' => function ($url, $model) {
                    $customurl = Yii::$app->getUrlManager()->createUrl([
                        '/pay/default/create/',
                        'user_id' => $model->id
                    ]);

                    return \yii\helpers\Html::a(
                        '<span class="fa fa-money"></span>', $customurl, [
                            'title' => Pay::t('pay-admin', 'Pay'),
                            'data-pjax' => '0',
                            'class' => 'btn btn-default btn-xs'
                        ]
                    );
                },
            ],
        ]
    ]
];

?>
<div class="box box-primary">
    <div class="box-header">
        <div class="pull-right">
            <?= Html::a('<i class="fa fa-list"></i>', ['index'],
                [
                    'class' => 'btn btn-default btn-sm',
                    'title' => Pay::t('pay-admin', 'List')
                ]); ?>
        </div>
    </div>
    <div class="box-body">
        <?= GridView::widget($gridConfig); ?>
    </div>
</div>