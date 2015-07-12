<?php

use frontend\modules\user\User;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\user\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = User::t('user-frontend', 'Personal Area');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <h4 class="text-capitalize"><?= User::t('user-frontend', 'Hello, {name}', ['name' => Yii::$app->user->identity->profile->name]); ?></h4>
    <hr />
    <?php
    // Предлагаем заполнить профиль
    if(empty(Yii::$app->user->identity->profile->surname) || empty(Yii::$app->user->identity->profile->gender)): ?>
        <div class="bg-danger" style="padding: 10px;">
            <?=
            User::t('user-frontend', 'Your profile is not full. Please fill it up, follow the link: {link}',
                    [
                        'link' => Html::a(User::t('user-frontend', 'Update profile'), ['update'])
                    ]
                )
            ?>
        </div>
        <hr />
    <?php endif; ?>
    <h5><?= User::t('user-frontend', 'Balance paid employment: {count}', ['count' => $dataProvider->getTotalCount()]); ?> </h5>
    <?php
    if ($dataProvider->getTotalCount() > 0) {
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'columns'      => [
                [
                    'attribute' => 'date',
                    'value'     => function ($model) {
                        return Yii::$app->formatter->asTime(strtotime($model->date), 'php:d.m.Y')
                                . ' (' . Yii::$app->formatter->asTime(strtotime($model->date), 'php:l') . ')';
                    }
                ],
                'timetable.start',
                'timetable.end',
                'timetable.group.name',
            ],
        ]);
    }
    ?>

</div>
