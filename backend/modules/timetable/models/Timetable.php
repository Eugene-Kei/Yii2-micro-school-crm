<?php

namespace backend\modules\timetable\models;

use Yii;
use Carbon\Carbon;
use backend\modules\group\models\Group;

/**
 * This is the model class for table "{{%timetable}}".
 *
 * @property integer $id
 * @property string $start
 * @property string $end
 * @property integer $week_day
 * @property integer $group_id
 *
 * @property Group $group
 */
class Timetable extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%timetable}}';
    }

    public static function getWeekArray() {
        return [
            1 => 'Понедельник',
            2 => 'Вторник',
            3 => 'Среда',
            4 => 'Четверг',
            5 => 'Пятница',
            6 => 'Суббота',
            0 => 'Воскресенье',
        ];
    }

    public static $staticTableView = '_staticTable';

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['week_day', 'group_id', 'start', 'end'], 'required', 'on' => ['timetable-update', 'timetable-create']],
            [['week_day', 'group_id', 'start', 'end'], 'safe', 'on' => ['timetable-edit']],
            ['group_id', 'integer'],
            ['group_id', 'exist', 'targetClass' => 'backend\modules\group\models\Group', 'targetAttribute' => 'id'],
            ['week_day', 'in', 'range' => array_keys(self::getWeekArray())],
            [['start', 'end'], 'match', 'pattern' => '/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        return [
            'timetable-create' => ['week_day', 'group_id', 'start', 'end'],
            'timetable-update' => ['week_day', 'group_id', 'start', 'end'],
            'timetable-clone'  => ['week_day', 'group_id', 'start', 'end'],
            'timetable-edit'   => ['week_day', 'group_id', 'start', 'end']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id'       => 'ID',
            'start'    => 'Начало',
            'end'      => 'Окончание',
            'week_day' => 'День недели',
            'group_id' => 'Группа',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup() {
        return $this->hasOne(Group::className(), ['id' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaidEmployment() {
        return $this->hasMany(PaidEmployment::className(), ['timetable_id' => 'id']);
    }

    /**
     * Обновляет даты в таблице paid_employment согласно обновленному расписанию
     * Если нечего обновлять или обновление прошло успешно, возвращает true
     * Если обновить не удалось, возвращает false
     * @return boolean 
     */
    public function updetePaidEmployment() {
        if (!isset($this->attributes['week_day']) || !isset($this->oldAttributes['week_day']) ||
                $this->attributes['week_day'] == $this->oldAttributes['week_day']) {
            return true;
        }

        $newWeekDay = Carbon::create()
                ->yesterday()
                ->next($this->attributes['week_day']);
        $oldWeekDay = Carbon::create()
                ->yesterday()
                ->next($this->oldAttributes['week_day']);
        $difference = $oldWeekDay->diffInDays($newWeekDay);
        if ($newWeekDay < $oldWeekDay) {
            $mysqlFunc = 'SUBDATE';
        } else {
            $mysqlFunc = 'ADDDATE';
        }
        $command = Yii::$app->db->createCommand('UPDATE  `paid_employment` '
//                . 'INNER JOIN `timetable` ON `paid_employment`.`timetable_id` >= `timetable`.`id`'
                . 'SET `date` = ' . $mysqlFunc . '(`date` , INTERVAL ' . $difference . ' DAY)'
                . ' WHERE `timetable_id` = ' . $this->oldAttributes['id']
                . ' AND `date` >= "' . date('Y-m-d') . '"'
        );

        return is_int($command->execute());
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {

            $this->start = ($this->start) ? Carbon::createFromFormat('H:i', $this->start)->toTimeString() : NULL;
            $this->end   = ($this->end) ? Carbon::createFromFormat('H:i', $this->end)->toTimeString() : NULL;

            if (!$insert) {
                return $this->updetePaidEmployment();
            }
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function afterFind() {
        $this->start = ($this->start) ? date('H:i', strtotime($this->start)) : NULL;
        $this->end   = ($this->end) ? date('H:i', strtotime($this->end)) : NULL;
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes) {
        if (parent::afterSave($insert, $changedAttributes)) {
            //иначе, после обновления значения становятся H:i:s (конвертируются в методе beforeSave())
            $this->start = ($this->start) ? date('H:i', strtotime($this->start)) : NULL;
            $this->end   = ($this->end) ? date('H:i', strtotime($this->end)) : NULL;
        }
    }

}
