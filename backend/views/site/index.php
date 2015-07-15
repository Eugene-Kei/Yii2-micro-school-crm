<?php


use backend\modules\statistics\Statistics;
use backend\modules\timetable\Module as Timetable;
use backend\modules\employment\Employment;
use backend\modules\pay\Pay;
use backend\modules\user\Module as User;
use yii\helpers\Url;


/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Admin panel');
?>

<div class="row center-block text-center center">
    <?php
    if(Yii::$app->user->can('/user/*')):
        ?>
        <div class="col-lg-3 col-md-4 col-xs-6">
            <a class="btn btn-default btn-square-200px"
               href="<?php echo Url::toRoute('/user/default/index'); ?>">
                <i class="fa fa-user fa-10x text-info">
                </i>
                <br/>
                <h4><?= User::t('user-admin', 'Users') ?></h4>
            </a>
        </div>
    <?php
    endif;
    if(Yii::$app->user->can('/pay/*')):
        ?>
        <div class="col-lg-3 col-md-4 col-xs-6">
            <a class="btn btn-default btn-square-200px"
               href="<?php echo Url::toRoute('/pay/default/index'); ?>">
                <i class="fa fa-money fa-10x text-info">
                </i>
                <br/>
                <h4><?= Pay::t('pay-admin', 'Pays') ?></h4>
            </a>
        </div>
    <?php
    endif;
    if(Yii::$app->user->can('/employment/*')):
        ?>
        <div class="col-lg-3 col-md-4 col-xs-6">
            <a class="btn btn-default btn-square-200px"
               href="<?php echo Url::toRoute('/employment/default/index'); ?>">
                <i class="fa fa-clock-o fa-10x text-info">
                </i>
                <br/>
                <h4><?= Employment::t('employment', 'Paid employments') ?></h4>
            </a>
        </div>
    <?php
    endif;
    if(Yii::$app->user->can('/user/*')):
        ?>
        <div class="col-lg-3 col-md-4 col-xs-6">
            <a class="btn btn-default btn-square-200px"
               href="<?php echo Url::toRoute('/user/default/debtor'); ?>">
                <i class="fa fa-money fa-10x text-danger">
                </i>
                <br/>
                <h4><?= User::t('user-admin', 'List debtors') ?></h4>
            </a>
        </div>
    <?php
    endif;
    if(Yii::$app->user->can('/timetable/*')):
        ?>
        <div class="col-lg-3 col-md-4 col-xs-6">
            <a class="btn btn-default btn-square-200px"
               href="<?php echo Url::toRoute('/timetable/default/view'); ?>">
                <i class="fa fa-calendar fa-10x text-info">
                </i>
                <br/>
                <h4><?= Timetable::t('timetable-admin', 'Timetable') ?></h4>
            </a>
        </div>
    <?php
    endif;
    if(Yii::$app->user->can('/timetable/*')):
        ?>
        <div class="col-lg-3 col-md-4 col-xs-6">
            <a class="btn btn-default btn-square-200px"
               href="<?php echo Url::toRoute('/timetable/default/cancel-lessons'); ?>">
                <i class="fa fa-close fa-10x text-danger">
                </i>
                <br/>
                <h4><?= Timetable::t('timetable-admin', 'Cancel lessons') ?></h4>
            </a>
        </div>
    <?php
    endif;
    if(Yii::$app->user->can('/statistics/*')):
        ?>
        <div class="col-lg-3 col-md-4 col-xs-6 center-block">
            <a class="btn btn-default btn-square-200px"
               href="<?php echo Url::toRoute('/statistics/default/index'); ?>">
                <i class="fa fa-bar-chart fa-10x text-info">
                </i>
                <br/>
                <h4><?= Statistics::t('statistics', 'Statistics') ?></h4>
            </a>
        </div>
    <?php endif;?>
</div>
