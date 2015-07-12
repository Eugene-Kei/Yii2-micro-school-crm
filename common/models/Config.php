<?php

namespace common\models;

use yii\helpers\HtmlPurifier;
use backend\modules\config\Config as Module;

/**
 * This is the model class for table "{{%config}}".
 *
 * @property integer $id
 * @property string $param
 * @property string $value
 * @property string $default
 * @property string $label
 * @property string $type
 */
class Config extends \yii\db\ActiveRecord {

    /**
     * Тип свойства. Определяем в контроллере по значению GET параметра.
     * Если значение 0 или '' то в базу передается null.
     * Чтобы этого не происходило задаем принудительно в beforesave
     * @var string
     */
    public $typeValue = null;
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%config}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['value', 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id'      => Module::t('configuration', 'ID'),
            'param'   => Module::t('configuration', 'Param'),
            'value'   => Module::t('configuration', 'Value'),
            'default' => Module::t('configuration', 'Default value'),
            'label'   => Module::t('configuration', 'Parameter name'),
            'type'    => Module::t('configuration', 'Type'),
        ];
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {

            $this->value = ($this->value) ? HtmlPurifier::process($this->value, [
                        'Attr.EnableID'      => true,
                        'HTML.Allowed'       => 'p, ul, li, b, i, a, pre, strong, br, span',
                        'HTML.AllowedAttributes'       => 'a.href, style, id, class, img.src',
                        'AutoFormat.Linkify' => true,
                    ]) : NULL;
            
            if($this->typeValue !== null){
                switch ($this->typeValue){
                case 'integer': 
                    $this->value = (int) $this->value;
                    break;
                default:
                    $this->value = (string) $this->value;
                    break;
                }
            }

            return true;
        }
        return false;
    }

}
