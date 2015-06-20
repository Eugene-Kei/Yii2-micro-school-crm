<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'rbac' => [
            'class' => 'mdm\admin\Module',
            'controllerMap' => [
                'assignment' => [
                    'class' => 'mdm\admin\controllers\AssignmentController',
                    'idField' => 'id', // id field of model User
                    'usernameField' => 'email',
                ]
            ],
            'viewPath' => '@app/themes/adminlte/yii2-admin'
        ],
        'config' => [
            'class' => 'backend\modules\config\Config',
        ],
        'news' => [
            'class' => 'eugenekei\news\Module',
            'controllerNamespace' => 'eugenekei\news\controllers\backend',
            'viewPath' => '@app/themes/adminlte/yii2-simple-news'
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ]
    ],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => '@app/themes/adminlte'
                ],
            ],
        ],
        'urlManager' => [
            'rules' => [
                '' => 'site/index',
                '<_a:(login)>' => 'site/<_a>',
                '<_m>/<_c>/<_a>' => '<_m>/<_c>/<_a>',
                '<_m>/<_c>' => '<_m>/<_c>',
                '<_m>' => '<_m>',
            ]
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            'rbac/*', // add or remove allowed actions to this list
            'site/login',
            'site/logout',
            'debug/default*',
        ]
    ],
    'params' => $params,
];
