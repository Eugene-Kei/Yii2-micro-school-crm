<?php

use eugenekei\news\Module;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model eugenekei\news\models\News */

$this->title = Module::t('eugenekei-news', 'News');
$this->params['subtitle'] = Module::t('eugenekei-news', 'View News');
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->params['subtitle'];


$htmlPurifierOptions = [
    'HTML.SafeIframe' => true,
    'Attr.AllowedFrameTargets' => ['_blank', '_self', '_parent', '_top'],
    'URI.SafeIframeRegexp' =>
        '%^(https?:)?//(www.youtube.com/embed/|player.vimeo.com/video/|vk.com/video)%',
];
?>
<div class="news-view">
    <div class="box box-default">
        <div class="box-header">
            <div class="pull-right">
                <?= Html::a('<i class="glyphicon glyphicon-list"></i>', ['index'],
                    [
                        'class' => 'btn btn-default btn-sm',
                        'title' => Module::t('eugenekei-news', 'List')
                    ]); ?>
                <?= Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['update', 'id' => $model->id],
                    [
                        'class' => 'btn btn-success btn-sm',
                        'title' => Module::t('eugenekei-news', 'Update')
                    ]); ?>
                <?= Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                    [
                        'class' => 'btn btn-primary btn-sm',
                        'title' => Module::t('eugenekei-news', 'Create')
                    ]); ?>
                <?= Html::a('<i class="glyphicon glyphicon-trash"></i>', ['delete', 'id' => $model->id],
                    [
                        'class' => 'btn btn-danger btn-sm',
                        'title' => Module::t('eugenekei-news', 'Delete'),
                        'data-confirm' => Module::t('eugenekei-news', 'Are you sure to delete this item?'),
                        'data-method' => 'post',
                    ]); ?>
            </div>
        </div>
        <div class="box-body">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'title',
                    [
                        'attribute' => 'annonce',
                        'format' => [
                            'html',
                            $htmlPurifierOptions
                        ]
                    ],
                    [
                        'attribute' => 'content',
                        'format' => [
                            'html',
                            $htmlPurifierOptions
                        ],
                    ],
                    [
                        'attribute' => 'status',
                        'value' => $model->getStatusArray()[$model->status]
                    ],
                    'created_at:datetime',
                    'updated_at:datetime',
                    [
                        'attribute' => 'user_id',
                        'value' => $model->{Yii::$app->controller->module->authorModel}
                            ->{Yii::$app->controller->module->authorNameField}
                    ]
                ],
            ]);
            ?>


        </div>
    </div>
</div>
