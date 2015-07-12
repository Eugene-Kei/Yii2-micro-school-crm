<?php

namespace backend\modules\group\models;

use backend\modules\group\Module;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%group}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 */
class Group extends \yii\db\ActiveRecord
{

    const ACTIVE_STATUS = 1;
    const INACTIVE_STATUS = 0;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%group}}';
    }

    /**
     * Массив ['id'=>'title'] всех активных групп
     * @return array
     */
    static function getGroupArray()
    {
        return ArrayHelper::map(
            self::find()
                ->asArray()
                ->select('id, name')
                ->where(['status' => self::ACTIVE_STATUS])
                ->orderBy('name ASC')
                ->all(),
            'id',
            'name');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 80],
            [['description'], 'string', 'max' => 255],
            [['status'], 'in', 'range' => array_keys(self::getStatusArray())],
            [['status'], 'default', 'value' => '0'],
        ];
    }

    public static function getStatusArray()
    {
        return [
            self::ACTIVE_STATUS => Module::t('group-admin', 'Active'),
            self::INACTIVE_STATUS => Module::t('group-admin', 'Inactive'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('group-admin', 'ID'),
            'name' => Module::t('group-admin', 'Name'),
            'description' => Module::t('group-admin', 'Description'),
            'status' => Module::t('group-admin', 'Status'),
        ];
    }

}
