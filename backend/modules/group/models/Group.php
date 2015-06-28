<?php

namespace backend\modules\group\models;

use yii\helpers\ArrayHelper;
use Yii;
use backend\modules\group\Module;

/**
 * This is the model class for table "{{%group}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 */
class Group extends \yii\db\ActiveRecord {

    const ACTIVE_STATUS = 1;
    const INACTIVE_STATUS = 0;


    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%group}}';
    }

    public static function getStatusArray(){
        return [
            self::ACTIVE_STATUS => Module::t('group-admin', 'Active'),
            self::INACTIVE_STATUS => Module::t('group-admin', 'Inactive'),
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 80],
            [['description'], 'string', 'max' => 255],
            [['status'], 'in', 'range' => array_keys(self::getStatusArray())],
            [['status'], 'default', 'value' => '0'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => Yii::t('group-admin', 'Name'),
            'description' => Yii::t('group-admin', 'Description'),
            'status' => Yii::t('group-admin', 'Status'),
        ];
    }
    
    /**
     * Массив ['id'=>'title'] всех активных рекламных источников
     * @return type
     */
    static function getGroupArray(){
        return ArrayHelper::map(
                self::find()
                ->asArray()
                ->select('id, name')
                ->orderBy('name ASC')
                ->all(),
                'id', 
                'name');
    }

}
