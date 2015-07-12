<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use frontend\modules\user\User;


/* @var $this yii\web\View */
/* @var $model \common\models\User */
/* @var $isActiveAffiliateProgram backend\modules\pay\models\Pay::isActiveAffiliateProgram(); */

$this->title                   = User::t('user-frontend', 'Profile');
$this->params['breadcrumbs'][] = ['label' => User::t('user-frontend', 'Personal Area'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">


    <?=
    DetailView::widget([
        'model'      => $model,
        'attributes' => [
            'profile.FullAvatarUrl:image:' . Yii::t('app', 'Avatar'),
            'id',
            'profile.surname',
            'profile.name',
            'profile.middle_name',
            'phone',
            'email:email',
            [
                'attribute' => 'profile.gender',
                'value' => $model->profile->genderArray[$model->profile->gender]
            ],
            'profile.birthday:date',
            'profile.balance',
            'profile.bonus_balance',
            'created_at:datetime',
            'updated_at:datetime',
            [
                'label'  => User::t('user-frontend', 'Referral link to the home page'),
                'format' => 'raw',
                'value'  => ($isActiveAffiliateProgram) ?
                        Html::input(
                                'text', 'ref-link', Yii::$app->request->getHostInfo() . '/?ref=' . $model->id, 
                            [
                                'class'    => 'form-control input-sm',
                                'readonly' => 'readonly',
                                'onclick' => 'this.select();',
                            ]
                        ) :
                    User::t('user-frontend', 'Link available')
            ],
        ],
    ])
    ?>

</div>
