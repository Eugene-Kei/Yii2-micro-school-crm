<?php
/* @var $this yii\web\View */
use frontend\widgets\AdminLteSideNav as Nav;
use eugenekei\news\Module as News;
use backend\modules\config\Config;

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
                        'label' => Config::t('module', 'Configuration'),
                        'url' => ['/config/default/index'],
                        'visible' => Yii::$app->user->can('/news/admin/*'),
                        'icon' => Config::getIcon(),
                    ],
                    [
                        'label' => News::t('module', 'News'),
                        'url' => ['/news/admin/index'],
                        'visible' => Yii::$app->user->can('/news/admin/*'),
                        'icon' => News::getIcon(),
                        'active' => $moduleId === 'news',
                    ],
                    [
                        'label' => 'Gii',
                        'url' => ['/gii'],
                        'visible' => Yii::$app->user->can('/gii/*'),
                        'icon' => 'fa-file-code-o',
                        'template' => '<a href="{url}" target="_blank">{icon} {label} {arrow}</a>'
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
                            ],
                            [
                                'label' => Yii::t('rbac-admin', 'Menus'),
                                'url' => ['/rbac/menu/index'],
                                'active' => $controllerPath === '/rbac/menu'
                            ],
                        ]
                    ],
                ],
            ]
        );
        ?>



    </section>

</aside>
