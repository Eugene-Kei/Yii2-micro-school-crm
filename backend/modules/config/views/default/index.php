<?php

use kartik\grid\GridView;
use backend\modules\config\Config;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = Config::t('configuration', 'Configuration');
$this->params['breadcrumbs'][] = $this->title;

$gridId = 'config-grid';
?>
<div class="<?= $gridId ?>">
    <div class="box box-default table-responsive">
        <div class="box-body">

            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'id' => $gridId,
                'filterModel' => $searchModel,
                'export' => false,
                'columns' => [
                    [
                        'attribute' => 'label',
                        'contentOptions' => ['style' => 'min-width: 320px;']
                    ],
                    [
                        'attribute' => 'value',
                        'filter' => false,
                        'enableSorting' => false,
                        'format' => 'html',
                        'class' => 'kartik\grid\EditableColumn',
                        'editableOptions' => function ($model, $key, $index) {
                            $arrayData = unserialize($model->data);
                            $options = [
                                'inputType' => constant('kartik\editable\Editable::' . $model->type),
                                'type' => 'post',
                                'format' => 'button',
                                'size' => 'md',
                                'placement' => 'left',
                                'formOptions' => [
                                    'action' => yii\helpers\Url::toRoute('edit'),
                                    'id' => 'config-form',
                                ],
                            ];
                            if (is_array($arrayData)) {
                                $options['displayValueConfig'] = $arrayData;
                                $options['data'] = $arrayData;
                            }

                            return $options;
                        },
                    ],
                    [
                        'attribute' => 'default',
                        'filter' => false,
                        'format' => 'html',
                        'enableSorting' => false,
                        'value' => function ($model) {
                            $array = unserialize($model->data);

                            return !empty($array[$model->default]) ? $array[$model->default] : $model->default;
                        },
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
</div>
