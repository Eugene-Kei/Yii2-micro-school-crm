<?php
/* @var $this yii\web\View */
use frontend\widgets\AdminLteSideNav as Nav;
use eugenekei\news\Module as News;
use backend\modules\config\Config;
use backend\modules\user\Module as User;
use backend\modules\group\Module as Group;
use backend\modules\timetable\Module as Timetable;
use backend\modules\employment\Employment;
use backend\modules\pay\Pay;
use backend\modules\ticket\Ticket;
use backend\modules\statistics\Statistics;

if (!isset(Yii::$app->i18n->translations['rbac-admin'])) {
    Yii::$app->i18n->translations['rbac-admin'] = [
        'class' => 'yii\i18n\PhpMessageSource',
        'sourceLanguage' => 'en',
        'basePath' => '@mdm/admin/messages'
    ];
}

$controllerPath = '';
$moduleId = '';
if(Yii::$app->controller->module->id) {
    $controllerPath ='/'.Yii::$app->controller->module->id;
    $moduleId = Yii::$app->controller->module->id;
}
$controllerPath .= '/'.Yii::$app->controller->id;


?>
<aside class="main-sidebar">

    <section class="sidebar">
        <?php
        echo Nav::widget(
            [
                'id' => 'adminlte-sidebar-menu',
                'encodeLabels' => false,
                'options' => ['class'=>'sidebar-menu'],
                'activateItems' => true,
                'activateParents' => true,
                'items' => [
                    [
                        'label' => Config::t('configuration', 'Configuration'),
                        'url' => ['/config/default/index'],
                        'visible' => Yii::$app->user->can('/config/*'),
                        'icon' => Config::getIcon(),
                    ],
                    [
                        'label' => User::t('user-admin', 'Users'),
                        'url' => ['/user/default/index'],
                        'visible' => Yii::$app->user->can('/user/*'),
                        'icon' => User::getIcon(),
                    ],
                    [
                        'label' => Group::t('group-admin', 'Groups'),
                        'url' => ['/group/default/index'],
                        'visible' => Yii::$app->user->can('/group/*'),
                        'icon' => Group::getIcon(),
                    ],
                    [
                        'label' => Timetable::t('timetable-admin', 'Timetable'),
                        'url' => ['/timetable/default/index'],
                        'visible' => Yii::$app->user->can('/timetable/*'),
                        'icon' => Timetable::getIcon(),
                    ],
                    [
                        'label' => Ticket::t('ticket', 'Tickets'),
                        'url' => ['/ticket/default/index'],
                        'visible' => Yii::$app->user->can('/ticket/*'),
                        'icon' => Ticket::getIcon(),
                    ],
                    [
                        'label' => Pay::t('pay-admin', 'Pays'),
                        'url' => ['/pay/default/index'],
                        'visible' => Yii::$app->user->can('/pay/*'),
                        'icon' => Pay::getIcon(),
                    ],
                    [
                        'label' => Employment::t('employment', 'Paid employments'),
                        'url' => ['/employment/default/index'],
                        'visible' => Yii::$app->user->can('/employment/*'),
                        'icon' => Employment::getIcon(),
                    ],
                    [
                        'label' => News::t('eugenekei-news', 'News'),
                        'url' => ['/news/admin/index'],
                        'visible' => Yii::$app->user->can('/news/admin/*'),
                        'icon' => News::getIcon(),
                        'active' => $moduleId === 'news',
                    ],
                    [
                        'label' => Statistics::t('statistics', 'Statistics'),
                        'url' => ['/statistics/default/index'],
                        'visible' => Yii::$app->user->can('/statistics/*'),
                        'icon' => Statistics::getIcon(),
                    ],
                    [
                        'label' => Yii::t('rbac-admin', 'Assignments'),
                        'url' => ['/rbac'],
                        'icon' => 'fa-legal',
                        'visible' => Yii::$app->user->can('/rbac/*'),
                        'active' => $moduleId === 'rbac',
                        'items' => [
                            [
                                'label' => Yii::t('rbac-admin', 'Assignments'),
                                'url' => ['/rbac/assignment/index'],
                                'active' => $controllerPath === '/rbac/assignment'
                            ],
                            [
                                'label' => Yii::t('rbac-admin', 'Permissions'),
                                'url' => ['/rbac/permission/index'],
                                'active' => $controllerPath === '/rbac/permission'
                            ],
                            [
                                'label' => Yii::t('rbac-admin', 'Roles'),
                                'url' => ['/rbac/role/index'],
                                'active' => $controllerPath === '/rbac/role'
                            ],
                            [
                                'label' => Yii::t('rbac-admin', 'Routes'),
                                'url' => ['/rbac/route/index'],
                                'active' => $controllerPath === '/rbac/route'
                            ],
                            [
                                'label' => Yii::t('rbac-admin', 'Rules'),
                                'url' => ['/rbac/rule/index'],
                                'active' => $controllerPath === '/rbac/rule'
                            ]
                        ]
                    ],
                ],
            ]
        );
        ?>



    </section>

</aside>

<?php //print_r(Yii::$app->i18n->translations);?>