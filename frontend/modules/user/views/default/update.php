<?php

use frontend\modules\user\User;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$this->title = User::t('user-frontend', 'Update profile');
$this->params['breadcrumbs'][] = ['label' => User::t('user-frontend', 'Personal Area'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-update">

    <?= $this->render('_form', ['profile' => $profile]) ?>

</div>
