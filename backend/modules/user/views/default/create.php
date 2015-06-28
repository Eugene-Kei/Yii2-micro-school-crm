<?php

use yii\helpers\Html;
use backend\modules\user\Module;


/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $profile common\models\Profile */

$this->title = Module::t('user-admin', 'Users');
$this->params['subtitle'] = Module::t('user-admin', 'Create user');
$this->params['breadcrumbs'][] = [
    'label' => $this->title, 
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->params['subtitle'];
?>
<div class="user-create">
    <div class="box box-primary">
        <div class="box-header">
            <div class="pull-right">
                <?= Html::a('<i class="fa fa-list"></i>', ['index'],
                                    [
                                        'class' => 'btn btn-default btn-sm',
                                        'title' => Module::t('user-admin', 'List')                                    ]); ?>
            </div>
        </div>
        <div class="box-body">
            <?= $this->render('_form', compact('user', 'profile')); ?>

        </div>
    </div>
</div>
