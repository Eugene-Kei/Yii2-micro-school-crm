<?php

use yii\helpers\Html;
use yii\grid\GridView;
use frontend\modules\user\User;

/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\user\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                 = User::t('user-frontend', 'Payment history');
$this->params['breadcrumbs'] = [
    [
        'label' => User::t('user-frontend', 'Personal Area'),
        'url'   => ['default/index'],
    ],
    $this->title
];
?>
<div class="user-index">

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns'      => [
            [
                'attribute' => 'create_at',
            ],
            [
                'attribute' => 'ticket_id',
                'value'     => function ($model) {
                    return $model->ticket->title;
                },
            ],
            'current_cost',
            'cash',
            'bonus_cash',
            [
                'header'   => User::t('user-frontend', 'Actions'),
                'class'   => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view-pay' => function($url, $model, $key) {
                        return Html::a(
                            '<i class="glyphicon glyphicon-eye-open"></i>',
                            $url,
                            [
                                'title' => User::t('user-frontend', 'View details'),
                                'class' => 'btn btn-primary btn-xs'
                            ]
                        );
                    },
                        ],
                        'template'     => '{view-pay}'
                    ]
                ],
            ]);
            ?>

</div>
