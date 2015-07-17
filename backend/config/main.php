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
        'user' => [
            'class' => 'backend\modules\user\Module',
        ],
        'group' => [
            'class' => 'backend\modules\group\Module',
        ],
        'timetable' => [
            'class' => 'backend\modules\timetable\Module',
        ],
        'ticket' => [
            'class' => 'backend\modules\ticket\Ticket',
        ],
        'pay' => [
            'class' => 'backend\modules\pay\Pay',
        ],
        'employment' => [
            'class' => 'backend\modules\employment\Employment',
        ],
        'news' => [
            'class' => 'eugenekei\news\Module',
            'controllerNamespace' => 'eugenekei\news\controllers\backend',
            'authorNameField' => 'fullName',
            'viewPath' => '@app/themes/adminlte/yii2-simple-news'
        ],
        'statistics' => [
            'class' => 'backend\modules\statistics\Statistics',
        ],
        'gridview' => [
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
            'site/login',
            'site/logout',
            'debug/default*',
        ]
    ],
    'params' => $params,
];
