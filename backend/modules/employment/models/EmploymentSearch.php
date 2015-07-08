<?php

namespace backend\modules\employment\models;

use common\models\Profile;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\group\models\Group;
use backend\modules\employment\models\PaidEmployment;
use backend\modules\timetable\models\Timetable;

/**
 * EmploymentSearch represents the model behind the search form about `backend\modules\employment\models\PaidEmployment`.
 */
class EmploymentSearch extends PaidEmployment
{
    
    public $userFullName;
    public $timetableGroupId;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'pay_id', 'timetable_id', 'userFullName', 'timetableGroupId'], 'integer'],
            [['date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = PaidEmployment::find()->joinWith(['pay.profile', 'pay', 'timetable', 'timetable.group']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => [
                'defaultOrder' => [
                    'date' => SORT_DESC,
                ]
            ]
        ]);
        
        $dataProvider->sort->attributes['pay.profile.fullName'] = [
                'asc' => [
                    Profile::tableName().'.name' => SORT_ASC,
                    Profile::tableName().'.surname' => SORT_ASC,
                    ],
                'desc' => [
                    Profile::tableName().'.name' => SORT_DESC,
                    Profile::tableName().'.surname' => SORT_DESC,
                    ],
            ];
        $dataProvider->sort->attributes['timetable.group.name'] = [
                'asc' => [
                    Group::tableName().'.name' => SORT_ASC,
                    ],
                'desc' => [
                    Group::tableName().'.name' => SORT_DESC,
                    ],
            ];
        $dataProvider->sort->attributes['timetable.start'] = [
                'asc' => [
                    Timetable::tableName().'.start' => SORT_ASC,
                    ],
                'desc' => [
                    Timetable::tableName().'.start' => SORT_DESC,
                    ],
            ];
        $dataProvider->sort->attributes['timetable.end'] = [
                'asc' => [
                    Timetable::tableName().'.end' => SORT_ASC,
                    ],
                'desc' => [
                    Timetable::tableName().'.end' => SORT_DESC,
                    ],
            ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'date' => $this->date,
            'pay_id' => $this->pay_id,
            'pay.user_id' => $this->userFullName,
            'timetable.group_id' => $this->timetableGroupId,
        ]);

        return $dataProvider;
    }
}
