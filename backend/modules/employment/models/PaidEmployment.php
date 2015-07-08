<?php

namespace backend\modules\employment\models;

use backend\modules\employment\Employment;
use backend\modules\pay\models\Pay;
use backend\modules\timetable\models\Timetable;


/**
 * This is the model class for table "{{%paid_employment}}".
 *
 * @property integer $id
 * @property string $date
 * @property integer $pay_id
 * @property integer $timetable_id
 *
 * @property Timetable $timetable
 * @property Pay $pay
 */
class PaidEmployment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%paid_employment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'pay_id'], 'required'],
            [['date'], 'date', 'format'=>'php:Y-m-d'],
            [['pay_id', 'timetable_id'], 'integer'],
            ['pay_id', 'exist', 'targetAttribute' => 'id', 'targetClass' => Pay::className()],
            ['timetable_id', 'exist', 'targetAttribute' => 'id', 'targetClass' => Timetable::className()],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => Employment::t('employment', 'Date'),
            'max_date' => Employment::t('employment', 'Date'),
            'pay_id' => Employment::t('employment', 'Pay'),
            'userFullName;' => Employment::t('employment', 'User'),
            'timetable_id' => 'Timetable ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTimetable()
    {
        return $this->hasOne(Timetable::className(), ['id' => 'timetable_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPay()
    {
        return $this->hasOne(Pay::className(), ['id' => 'pay_id']);
    }
}
