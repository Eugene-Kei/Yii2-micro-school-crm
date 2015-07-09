<?php

namespace backend\modules\timetable\models;

use yii\base\Model;

class TimetableCancel extends Model {

    public $ids;
    public $date;

    public function rules() {
        return [
            [['ids', 'date'], 'required'],
            ['date', 'match', 'pattern' => '/^(0[1-9]|[12][0-9]|3[01])[.](0[1-9]|1[012])[.](20)\d\d$/'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'ids'  => 'Занятия',
            'date' => 'Дата',
        ];
    }

}
