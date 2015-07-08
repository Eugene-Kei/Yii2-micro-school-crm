<?php

namespace backend\modules\employment\models;

use yii\base\Model;
use backend\modules\timetable\models\Timetable;
use backend\modules\employment\Employment;

class EmploymentUpdate extends Model {

    public $id;
    public $date;

    public function rules() {
        return [
            [['id', 'date'], 'required'],
            ['id', 'integer', 'min' => 1],
            ['id', 'exist', 'targetAttribute' => 'id', 'targetClass' => Timetable::className()],
            ['date', 'match', 'pattern' => '/^(20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id'   => Employment::t('employment', 'Classes'),
            'date' => Employment::t('employment', 'Date'),
        ];
    }

}
