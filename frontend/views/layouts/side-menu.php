<?php

use kartik\sidenav\SideNav;
use yii\helpers\Url;
use frontend\modules\user\User;

$items = [
    [
        'label'  => User::t('user-frontend', 'Personal Area'),
        'url'    => Url::toRoute(['index']),
        'active' => (Url::to('') == Url::to(['index']))
    ],
    [
        'label' => User::t('user-frontend', 'Profile'),
        'url'   => Url::toRoute(['view']),
        'active' => (Url::to('') == Url::to(['view']))
    ],
    [
        'label' => User::t('user-frontend', 'Update profile'),
        'url'   => Url::toRoute(['update']),
        'active' => (Url::to('') == Url::to(['update']))
    ],
    [
        'label' => User::t('user-frontend', 'Payment history'),
        'url'   => Url::toRoute(['history']),
        'active' => (Url::to('') == Url::to(['history']))
    ],
];

echo SideNav::widget(
        [
            'heading' => User::t('user-frontend', 'Nav'),
            'items'   => $items,
            'type'    => SideNav::TYPE_DEFAULT,
            'activeCssClass' => 'side-menu-active'
        ]
);