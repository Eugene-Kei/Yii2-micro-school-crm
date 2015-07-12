<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
$avatarUrl = Yii::$app->user->identity->profile->fullAvatarUrl;
$userName = Yii::$app->user->identity->profile->fullName;
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini"><span class="fa fa-3x fa-home"></span></span>
<span class="logo-lg">' . Yii::$app->config->get('CONTACT.ORGANIZATION_NAME') . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= $avatarUrl ?>" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?= $userName ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= $avatarUrl ?>" class="img-circle" alt="User Image"/>

                            <p>
                                <?= $userName ?>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <?= Html::a(
                                    Yii::t('app', 'Profile'),
                                    ['/user/default/view', 'id' => Yii::$app->user->identity->id],
                                    ['class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    Yii::t('app', 'Logout'),
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </nav>
</header>
