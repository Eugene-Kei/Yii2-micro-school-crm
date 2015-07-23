<?php

namespace backend\modules\pay\models;

use yii\base\Model;
use backend\modules\ticket\models\SeasonTicket;
use yii\data\ActiveDataProvider;
use common\models\Profile;

/**
 * PaySearch represents the model behind the search form about `backend\modules\pay\models\Pay`.
 */
class PaySearch extends Pay {

    public $userFullName;
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['ticket_id', 'id', 'user_id'], 'integer'],
            ['ticket_id', 'exist', 'targetAttribute' => 'id', 'targetClass' => SeasonTicket::className()],
            [['comment', 'create_at', 'userFullName'], 'safe'],
            [['current_cost', 'cash', 'bonus_cash'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
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
    public function search($params) {
        $query = Pay::find()->joinWith(['profile', 'ticket']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => [
                'defaultOrder' => [
                    'create_at' => SORT_DESC
                ]
            ]
        ]);
        
        /**
     * Настройка параметров сортировки
     * Важно: должна быть выполнена раньше $this->load($params)
     * statement below
     */
     $dataProvider->sort->attributes['userFullName'] = [
                'asc' => [
                    Profile::tableName().'.name' => SORT_ASC,
                    Profile::tableName().'.surname' => SORT_ASC,
                    ],
                'desc' => [
                    Profile::tableName().'.name' => SORT_DESC,
                    Profile::tableName().'.surname' => SORT_DESC,
                    ],
            ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id'           => $this->id,
            Pay::tableName().'.user_id'      => $this->user_id,
            'ticket_id'    => $this->ticket_id,
            'current_cost' => $this->current_cost,
            'cash'         => $this->cash,
            'bonus_cash'   => $this->bonus_cash,
        ]);

        $query->andFilterWhere($this->getBetweenDatesFilterArray('create_at', ' - ', 'Y-m-d H:i:s'))
            ->andFilterWhere(['like', 'comment', $this->comment]);

        $query->andFilterWhere(['like', 'CONCAT(" ", '
            .Profile::tableName().'.name, '
            .Profile::tableName().'.surname) ', 
            $this->userFullName
                ]);

        return $dataProvider;
    }

    /**
     * Преобразует строку переданную виджетом kartik\daterange\DateRangePicker
     * в массив параметров для andFilterWhere, вида ['between', $fieldName, $startDate, $endDate].
     * @param string $fieldName Имя проверяемого поля
     * @param string $separator Строка-разделитель между датами начала поиска и конца
     * @param string $timeFormat формат вывода даты php фуркции date(), например - 'Y-m-d H:i:s'.
     * @return array
     */
    public function getBetweenDatesFilterArray($fieldName, $separator, $timeFormat = 'U'){
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
            $item = date($timeFormat, strtotime(trim($item)));
            if(!$item){
                return [];
            }
            $filterArray[] = $item;
        }
        return $filterArray;
    }

}
