<?php

namespace backend\modules\user\models;

use common\models\Profile;
use common\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'string'],
            [
                [
                    'phone',
                    'email',
                    'profile.name',
                    'profile.surname',
                    'profile.middle_name'
                ],
                'safe'
            ],
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

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['profile.name', 'profile.surname', 'profile.middle_name']);
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
        $query = User::find()->joinWith(['profile']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['profile.name'] = [
            'asc' => [Profile::tableName() . '.name' => SORT_ASC],
            'desc' => [Profile::tableName() . '.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['profile.surname'] = [
            'asc' => [Profile::tableName() . '.surname' => SORT_ASC],
            'desc' => [Profile::tableName() . '.surname' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['profile.middle_name'] = [
            'asc' => [Profile::tableName() . '.middle_name' => SORT_ASC],
            'desc' => [Profile::tableName() . '.middle_name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere($this->getBetweenDatesFilterArray('created_at', ' - '))
            ->andFilterWhere($this->getBetweenDatesFilterArray('updated_at', ' - '))
            ->andFilterWhere(['LIKE', 'phone', $this->phone])
            ->andFilterWhere(['LIKE', 'email', $this->email])
            ->andFilterWhere(['LIKE', 'profile.name', $this->getAttribute('profile.name')])
            ->andFilterWhere(['LIKE', 'profile.surname', $this->getAttribute('profile.surname')])
            ->andFilterWhere(['LIKE', 'profile.middle_name', $this->getAttribute('profile.middle_name')]);

        return $dataProvider;
    }


    /**
     * Преобразует строку переданную виджетом kartik\daterange\DateRangePicker
     * в массив параметров для andFilterWhere, вида ['between', $fieldName, $startDate, $endDate].
     * @param string $fieldName Имя проверяемого поля
     * @param string $separator Строка-разделитель между датами начала поиска и конца
     * @return array
     */
    public function getBetweenDatesFilterArray($fieldName, $separator){
        if(empty($this->$fieldName)){
            return [];
        }

        $timeArray = explode($separator, $this->$fieldName);
        $datesArray = array_splice($timeArray, 0, 2);
        if(count($datesArray) < 2){
            return [];
        }

        $filterArray = ['between', $fieldName];

        foreach($datesArray as $item){
            $item = strtotime(trim($item));
            if(!$item){
                return [];
            }
            $filterArray[] = $item;
        }

        return $filterArray;
    }
}
