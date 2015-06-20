<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\AuthItem */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Permissions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="auth-item-view">

        <div class="box box-body">

            <p>
                <?= Html::a(Yii::t('rbac-admin', 'Update'), ['update', 'id' => $model->name], ['class' => 'btn btn-primary']) ?>
                <?php
                echo Html::a(Yii::t('rbac-admin', 'Delete'), ['delete', 'id' => $model->name], [
                    'class' => 'btn btn-danger',
                    'data-confirm' => Yii::t('rbac-admin', 'Are you sure to delete this item?'),
                    'data-method' => 'post',
                ]);
                ?>
            </p>

            <?php
            echo DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'name',
                    'description:ntext',
                    'ruleName',
                    'data:ntext',
                ],
            ]);
            ?>
            <div class="col-lg-5">
                <?= Yii::t('rbac-admin', 'Avaliable') ?>:
                <?php
                echo Html::textInput('search_av', '', ['class' => 'role-search', 'data-target' => 'avaliable']) . '<br>';
                echo Html::listBox('roles', '', $avaliable, [
                    'id' => 'avaliable',
                    'multiple' => true,
                    'size' => 20,
                    'style' => 'width:100%']);
                ?>
            </div>
            <div class="col-lg-1">
                &nbsp;<br><br>
                <?php
                echo Html::a('>>', '#', ['class' => 'btn btn-success', 'data-action' => 'assign']) . '<br>';
                echo Html::a('<<', '#', ['class' => 'btn btn-success', 'data-action' => 'delete']) . '<br>';
                ?>
            </div>
            <div class="col-lg-5">
                <?= Yii::t('rbac-admin', 'Assigned') ?>:
                <?php
                echo Html::textInput('search_asgn', '', ['class' => 'role-search', 'data-target' => 'assigned']) . '<br>';
                echo Html::listBox('roles', '', $assigned, [
                    'id' => 'assigned',
                    'multiple' => true,
                    'size' => 20,
                    'style' => 'width:100%']);
                ?>
            </div>
        </div>
    </div>
<?php
$this->render('_script', ['name' => $model->name]);
