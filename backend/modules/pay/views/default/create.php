<?php

use yii\helpers\Html;
use backend\modules\pay\Pay;

/* @var $this yii\web\View */
/* @var $model backend\modules\pay\models\Pay */
/* @var $profile common\models\Profile */
/* @var $tickets backend\modules\ticket\models\SeasonTicket */

$this->title = Pay::t('pay-admin', 'Pays');
$this->params['subtitle'] = Pay::t('pay-admin', 'Create pay');
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->params['subtitle'];
?>
<div class="pay-create">
    <div class="box box-primary">
        <div class="box-header">
            <div class="pull-right">
                <?= Html::a('<i class="fa fa-list"></i>', ['index'],
                    [
                        'class' => 'btn btn-default btn-sm',
                        'title' => Pay::t('pay-admin', 'List')
                    ]); ?>
            </div>
        </div>
        <div class="box-body">
            <?=
            $this->render('_form', [
                'model' => $model,
                'box' => $box,
                'profile' => $profile,
                'tickets' => $tickets,
                'groups' => $groups,
            ]);
            ?>
        </div>
    </div>
</div>
