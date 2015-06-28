<?php

use yii\helpers\Html;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use backend\modules\group\Module;
use backend\modules\group\models\Group;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\group\models\GroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('group-admin', 'Groups');
$this->params['subtitle'] = Module::t('group-admin', 'List groups');
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => ['index']
];

$this->params['breadcrumbs'][] = $this->params['subtitle'];

$gridId = 'group-grid';

$this->registerJs(
                "jQuery(document).on('click', '#batch-delete', function (evt) {" .
                    "evt.preventDefault();" .
                    "var keys = jQuery('#" . $gridId . "').yiiGridView('getSelectedRows');" .
                    "if (keys == '') {" .
                        "alert('" . Module::t('group-admin', 'You need to select at least one item.') . "');" .
                    "} else {" .
                        "if (confirm('" . Module::t('group-admin', 'Are you sure you want to delete selected items?') . "')) {" .
                            "jQuery.ajax({" .
                                "type: 'POST'," .
                                "url: jQuery(this).attr('href')," .
                                "data: {ids: keys}" .
                            "});" .
                        "}" .
                    "}" .
                "});"
            );
?>
<div class="<?= $gridId ?>">
    <div class="box box-default">
        <div class="box-header">
            <div class="pull-right">
                <?= Html::a('<i class="fa fa-plus"></i>', ['create'],
                    [
                        'class' => 'btn btn-primary btn-sm',
                        'title' => Module::t('group-admin', 'Create')
                    ]); ?>
                <?= Html::a('<i class="fa fa-trash"></i>', ['batch-delete'],
                    [
                        'class' => 'btn btn-danger btn-sm',
                        'id' => 'batch-delete',
                        'title' => Module::t('group-admin', 'Delete selected')
                    ]); ?>
            </div>
        </div>
        <div class="box-body">
            <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'id' => $gridId,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => CheckboxColumn::classname()],
//                    'id',
                    'name',
                    'description:html',
                    [
                        'attribute' => 'status',
                        'value' => function($data){return Group::getStatusArray()[$data->status];},
                        'filter' => Html::activeDropDownList(
                            $searchModel, 'status', Group::getStatusArray(), ['class' => 'form-control', 'prompt' => '']
                        )
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'buttonOptions' => ['class' => 'btn btn-default btn-xs'],
                        'headerOptions' => ['style' => 'width:95px;'],
                        'header' => Module::t('group-admin', 'Actions')
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
</div>
