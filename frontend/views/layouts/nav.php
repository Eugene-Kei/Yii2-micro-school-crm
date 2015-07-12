<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;



NavBar::begin([
    'brandLabel' => Yii::$app->config->get('CONTACT.ORGANIZATION_NAME'),
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);
$menuItems = [
    ['label' => Yii::t('app', 'Home'), 'url' => ['/site/index']],
    ['label' => Yii::t('app', 'News'), 'url' => ['/news/news/index']],
    ['label' => Yii::t('app', 'About'), 'url' => ['/site/about']],
    ['label' => Yii::t('app', 'Timetable'), 'url' => ['/site/timetable']],
    ['label' => Yii::t('app', 'Contact'), 'url' => ['/site/contact']],
];
if (Yii::$app->user->isGuest) {
    $menuItems[] = ['label' => Yii::t('app', 'Sign up'), 'url' => ['/site/signup']];
    $menuItems[] = ['label' => Yii::t('app', 'Login'), 'url' => ['/site/login']];
} else {
    $menuItems[] = ['label' => Yii::t('app', 'Cabinet'), 'url' => ['/cabinet/default/index']];
    $menuItems[] = [
        'label' => Yii::t('app', 'Logout'),
        'url' => ['/site/logout'],
        'linkOptions' => ['data-method' => 'post']
    ];
}
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $menuItems,
]);
NavBar::end();