<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%profile}}".
 *
 * @property integer $user_id
 * @property string $surname
 * @property string $name
 * @property string $middle_name
 * @property string $birthday
 * @property integer $gender
 * @property string $avatar_url
 * @property string $balance
 * @property string $bonus_balance
 * @property integer $user_affiliate_id
 *
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
{
    /** Inactive status */
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;

    /**
     * @var string Name regular pattern
     */
    public static $patternName = '/^([a-zа-яё])+(-[a-zа-яё]+)?+$/iu';

    /**
     * @var string Phone regular pattern
     */
    public $patternPhone = '/^(\+?[0-9]){5,30}$/';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%profile}}';
    }

    /**
     * @return string User full name
     */
    public function getFullName()
    {
        return $this->name . ' ' . $this->surname;
    }

    /**
     * @return array Gender array.
     */
    public static function getGenderArray()
    {
        return [
            self::GENDER_MALE => 'GENDER_MALE',
            self::GENDER_FEMALE => 'GENDER_FEMALE'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['birthday'], 'date'],
            ['user_affiliate_id', 'integer', 'min' => 1],
            [
                'user_affiliate_id',
                'compare',
                'compareAttribute' => 'user_id',
                'operator' => '!==',
                'message' => 'Нельзя быть аффилиатом самому себе'
            ],
            ['user_affiliate_id', 'exist', 'targetAttribute' => 'id', 'targetClass' => User::className()],
            ['gender', 'in', 'range' => array_keys(static::getGenderArray())],
            [['balance', 'bonus_balance'], 'number'],
            ['name', 'required'],
            [['surname', 'name', 'middle_name'], 'trim'],
            [['surname', 'name', 'middle_name'], 'string', 'max' => 50],
            [['surname', 'name', 'middle_name'], 'match', 'pattern' => static::$patternName],
            [['avatar_url'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'surname' => 'Surname',
            'name' => 'Name',
            'middle_name' => 'Middle Name',
            'birthday' => 'Birthday',
            'gender' => 'Gender',
            'avatar_url' => 'Avatar Url',
            'balance' => 'Balance',
            'bonus_balance' => 'Bonus Balance',
            'user_affiliate_id' => 'User Affiliate ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAffiliate()
    {
        return $this->hasOne(User::className(), ['id' => 'user_affiliate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfileAffiliate()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'user_affiliate_id']);
    }

}
