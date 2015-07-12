<?php

use yii\helpers\Html;
use eugenekei\news\Module;


/* @var $this yii\web\View */
/* @var $model eugenekei\news\models\News */

$this->title = Module::t('eugenekei-news', 'News');
$this->params['subtitle'] = Module::t('eugenekei-news', 'Create');
$this->params['breadcrumbs'][] = [
    'label' => $this->title, 
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->params['subtitle'];
?>
<div class="news-create">
    <div class="box box-primary">
        <div class="box-header">
            <div class="pull-right">
                <?= Html::a('<i class="glyphicon glyphicon-list"></i>', ['index'],
                                    [
                                        'class' => 'btn btn-default btn-sm',
                                        'title' => Module::t('eugenekei-news', 'List')                                    ]); ?>
            </div>
        </div>
        <div class="box-body">
            <?= $this->render('_form', [
                'model' => $model,
                'box' => $box,
            ]); ?>

        </div>
    </div>
</div>
