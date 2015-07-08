<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\grid\GridView;
use yii\grid\CheckboxColumn;
use yii\web\JsExpression;
use yii\helpers\Html;
use backend\modules\employment\Employment;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\employment\models\EmploymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $groupsArray array all exists groups as id=>name */

$this->title                   = Employment::t('employment', 'Paid employments');
$this->params['subtitle']      = Employment::t('employment', 'List of classes');
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url'   => ['index']
];
$this->params['breadcrumbs'][] = $this->params['subtitle'];

$gridId = 'paid-employment-grid';

$this->registerJs(
                "jQuery(document).on('click', '#batch-delete', function (evt) {" .
                    "evt.preventDefault();" .
                    "var keys = jQuery('#" . $gridId . "').yiiGridView('getSelectedRows');" .
                    "if (keys == '') {" .
                        "alert('" . Employment::t('employment', 'You need to select at least one item.') . "');" .
                    "} else {" .
                        "if (confirm('" . Employment::t('employment', 'Are you sure you want to delete selected items?') . "')) {" .
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
                <?= Html::a('<i class="fa fa-trash"></i>', ['batch-delete'],
                    [
                        'class' => 'btn btn-danger btn-sm',
                        'id' => 'batch-delete',
                        'title' => Employment::t('employment', 'Delete selected')
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
                    [
                        'attribute' => 'pay.profile.fullName',
                        'filterOptions' => ['style' => 'max-width: 200px;'],
                        'filter' => Select2::widget([
                                'id' => 'user_affiliate_id',
                                'model' => $searchModel,
                                'attribute' => 'userFullName',
                                'options' => [
                                    'placeholder' => Employment::t('employment', 'Start typing the name or surname'),
                                    'class' => 'form-control',
                                ],
                                'language' => 'ru',
                                'data' => $filterUserDataArray,
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'minimumInputLength' => 2,
                                    'ajax' => [
                                        'url' => \yii\helpers\Url::to(['/user/default/affiliate']),
                                        'dely' => 250,
                                        'type' => 'post',
                                        'dataType' => 'json',
                                        'data' => new JsExpression('function(term,page) { return {search:term}; }'),
                                        'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
                                    ],
                                ],
                            ]
                        )
                    ],
                    [
                        'attribute' => 'date',
                        'filter' => DatePicker::widget([
                                'model' => $searchModel,
                                'attribute' => 'date',
                                'type' => DatePicker::TYPE_INPUT,
                                'language' => 'ru',
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'yyyy-mm-dd'
                                ]
                            ]
                        )
                    ],
                    'timetable.start',
                    'timetable.end',
                    [
                        'label' => Employment::t('employment', 'Group'),
                        'attribute' => 'timetable.group.name',
                        'filter' => Html::activeDropDownList($searchModel, 'timetableGroupId', $groupsArray,
                            ['class' => 'form-control', 'prompt' => ''])
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'buttonOptions' => ['class' => 'btn btn-default btn-xs'],
                        'headerOptions' => ['style' => 'width:100px;'],
                        'header' => Employment::t('employment', 'Actions'),
                    ],
                ],
            ]);
            ?>

        </div>
    </div>
</div> 
