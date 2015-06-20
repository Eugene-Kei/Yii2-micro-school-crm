<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var mdm\admin\models\Route $model
 * @var ActiveForm $form
 */

$this->title = Yii::t('rbac-admin', 'Create route');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Routes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-body">

    <div class="create">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'route') ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('rbac-admin', 'Create'), ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>

    </div><!-- create -->
</div>
