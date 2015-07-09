<?php

/**
 * Users list view.
 *
 * @var \yii\base\View $this View
 * @var \yii\data\ActiveDataProvider $dataProvider Data provider
 * @var \vova07\users\models\backend\UserSearch $searchModel Search model
 * @var array $roleArray Roles array
 * @var array $statusArray Statuses array
 */
use yii\grid\GridView;
use backend\modules\user\Module;
use yii\grid\ActionColumn;
use yii\helpers\Html;

$module = new Module('user');

$this->title = Module::t('user-admin', 'Users');
$this->params['subtitle'] = Module::t('user-admin', 'List debtors');
$this->params['breadcrumbs'] = [
    $this->title
];
$gridId = 'users-debtor-grid';
$gridConfig = [
    'id' => $gridId,
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'label' => false,
            'format' => 'image',
            'value' => 'fullAvatarUrl',
            'contentOptions' => ['class' => 'grid-avatar'],
        ],
        'surname',
        'name',
        'middle_name',
        'balance'
    ]
];

$actions = ['{view}'];
if (Yii::$app->user->can('/pay/*')) {
    $actions[] = '{pay}';
    $actions[] = '{history}';
}


$boxButtons = [];
$showActions = true;

if ($showActions === true) {
    $gridConfig['columns'][] = [
        'class' => ActionColumn::className(),
        'buttonOptions' => ['class' => 'btn btn-default btn-xs'],
        'headerOptions' => ['style' => 'width:115px;'],
        'header' => Module::t('user-admin', 'Actions'),
        'template' => implode(' ', $actions),
        'buttons' => [
            'pay' => function ($url, $model) {
                $customurl = Yii::$app->getUrlManager()->createUrl([
                    '/pay/default/create/',
                    'user_id' => $model->user_id
                ]);

                return \yii\helpers\Html::a(
                    '<span class="fa fa-money"></span>', $customurl, [
                        'title' => Module::t('user-admin', 'Pay'),
                        'data-pjax' => '0',
                        'class' => 'btn btn-default btn-xs'
                    ]
                );
            },
            'history' => function ($url, $model) {
                $customurl = Yii::$app->getUrlManager()->createUrl([
                    '/pay/default/index/',
                    'PaySearch' => ['user_id' => $model->user_id]
                ]);

                return \yii\helpers\Html::a(
                    '<span class="fa fa-history"></span>', $customurl, [
                        'title' => Module::t('user-admin', 'Payment history'),
                        'data-pjax' => '0',
                        'class' => 'btn btn-default btn-xs'
                    ]
                );
            },
        ],
    ];
}
$boxButtons = !empty($boxButtons) ? implode(' ', $boxButtons) : null;
?>
<div class="user-debtor">
    <div class="box box-default">
        <div class="box-header">
            <div class="pull-right">
                <?= Html::a('<i class="fa fa-list"></i>', ['index'],
                    [
                        'class' => 'btn btn-default btn-sm',
                        'title' => Module::t('user-admin', 'List')
                    ]); ?>
            </div>
        </div>
        <div class="box-body">
            <?= GridView::widget($gridConfig); ?>
        </div>
    </div>
</div>
