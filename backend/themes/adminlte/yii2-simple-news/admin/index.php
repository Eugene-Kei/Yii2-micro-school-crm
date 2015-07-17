<?php

use eugenekei\news\Module;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $searchModel eugenekei\news\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('eugenekei-news', 'News');
$this->params['subtitle'] = Module::t('eugenekei-news', 'List News');
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->params['subtitle'];

$gridId = 'news-grid';
$statusArray = $searchModel->getStatusArray();

$this->registerJs(
                "jQuery(document).on('click', '#batch-delete', function (evt) {" .
                    "evt.preventDefault();" .
                    "var keys = jQuery('#" . $gridId . "').yiiGridView('getSelectedRows');" .
                    "if (keys == '') {" .
                        "alert('" . Module::t('eugenekei-news', 'You need to select at least one item.') . "');" .
                    "} else {" .
                        "if (confirm('" . Module::t('eugenekei-news', 'Are you sure you want to delete selected items?') . "')) {" .
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
                <?= Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                    [
                        'class' => 'btn btn-primary btn-sm',
                        'title' => Module::t('eugenekei-news', 'Create')
                    ]); ?>
                <?= Html::a('<i class="glyphicon glyphicon-trash"></i>', ['batch-delete'],
                    [
                        'class' => 'btn btn-danger btn-sm',
                        'id' => 'batch-delete',
                        'title' => Module::t('eugenekei-news', 'Delete selected')
                    ]); ?>
            </div>
        </div>
        <div class="box-body">

            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'id' => $gridId,
                'options' => ['class' => 'table-responsive'],
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => CheckboxColumn::classname()],
                    'title',
                    [
                        'attribute' => 'status',
                        'value' => function($model){return $model->getStatusArray()[$model->status];},
                        'filter' => Html::activeDropDownList(
                            $searchModel, 'status', $statusArray, ['class' => 'form-control', 'prompt' => '']
                        )
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => 'datetime',
                        'filter' => DateRangePicker::widget(
                            [
                                'model' => $searchModel,
                                'attribute' => 'created_at',
                                'convertFormat' => true,
                                'presetDropdown' => true,
                                'options' => [
                                    'class' => 'form-control',
                                ],
                                'pluginOptions' => [
                                    'format' => 'Y-m-d H:i:s',
                                    'dateLimit' => ['months' => 6],
                                    'opens' => 'left'
                                ],
                            ]
                        )
                    ],
                    [
                        'attribute' => 'user_id',
                        'value' => function($model){
                            $authorModel = Yii::$app->controller->module->authorModel;
                            $authorNameField = Yii::$app->controller->module->authorNameField;
                            return $model->$authorModel->$authorNameField;
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'buttonOptions' => ['class' => 'btn btn-default btn-xs'],
                        'headerOptions' => ['style' => 'width:95px;'],
                        'header' => Module::t('eugenekei-news', 'Actions')
                    ],
                ],
            ]); ?>

        </div>
    </div>
</div>
