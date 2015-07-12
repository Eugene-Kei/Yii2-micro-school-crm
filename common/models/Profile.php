<?php

namespace common\models;

use vova07\fileapi\behaviors\UploadBehavior;
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
    /** Gender */
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;

    /** Avatar settings */
    const AVATAR_URL = '/images/avatars';
    const AVATAR_UPLOAD_PATH = '@frontend/web/images/avatars';
    const AVATAR_UPLOAD_TEMP_PATH = '@frontend/web/images/avatars/tmp';
    const NO_AVATAR_FILENAME = 'default_avatar.gif';

    /**
     * @var string Name regular pattern
     */
    public static $patternName = '/^([a-zа-яё]+)(-[a-zа-яё]+)?$/iu';

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
    public static function getFullNameByUserId($id)
    {
        return self::findOne(['user_id' => $id])->getFullName();
    }

    /**
     * @return string User full name
     */
    public function getFullName()
    {
        return $this->name . ' ' . $this->surname;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['birthday'], 'date', 'format' => 'php:Y-m-d'],
            ['user_affiliate_id', 'integer', 'min' => 1],
            [
                'user_affiliate_id',
                'compare',
                'compareAttribute' => 'user_id',
                'operator' => '!==',
                'message' => Yii::t('app', 'Can not be yourself affiliate')
            ],
            ['gender', 'in', 'range' => array_keys(static::getGenderArray())],
            [['balance', 'bonus_balance'], 'number'],
            [['balance', 'bonus_balance'], 'default', 'value' => '0.00'],
            ['name', 'required'],
            [['surname', 'name', 'middle_name'], 'trim'],
            [['surname', 'name', 'middle_name'], 'string', 'max' => 50],
            [['name', 'surname', 'gender'], 'required', 'on' => 'frontend-update-own'],
            [['surname', 'name', 'middle_name'], 'match', 'pattern' => self::$patternName],
            [['avatar_url'], 'string', 'max' => 64],
            ['user_affiliate_id', 'exist', 'targetAttribute' => 'id', 'targetClass' => User::className()]
        ];
    }

    /**
     * @return array Gender array.
     */
    public static function getGenderArray()
    {
        return [
            self::GENDER_MALE => Yii::t('app', 'Male'),
            self::GENDER_FEMALE => Yii::t('app', 'Female')
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            'frontend-update-own' => ['birthday', 'gender', 'name', 'surname', 'middle_name', 'avatar_url'],
            'default' => [
                'birthday',
                'gender',
                'name',
                'surname',
                'middle_name',
                'user_affiliate_id',
                'balance',
                'bonus_balance',
                'avatar_url'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'ID',
            'surname' => Yii::t('app', 'Surname'),
            'name' => Yii::t('app', 'Name'),
            'middle_name' => Yii::t('app', 'Middle Name'),
            'fullName' => Yii::t('app', 'Full name'),
            'birthday' => Yii::t('app', 'Birthday'),
            'gender' => Yii::t('app', 'Gender'),
            'avatar_url' => Yii::t('app', 'Avatar'),
            'balance' => Yii::t('app', 'Balance'),
            'bonus_balance' => Yii::t('app', 'Bonus balance'),
            'user_affiliate_id' => Yii::t('app', 'Affiliate'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'uploadBehavior' => [
                'class' => UploadBehavior::className(),
                'attributes' => [
                    'avatar_url' => [
                        'path' => self::AVATAR_UPLOAD_PATH,
                        'tempPath' => self::AVATAR_UPLOAD_TEMP_PATH,
                        'url' => self::AVATAR_URL
                    ]
                ]
            ]
        ];
    }

    public function getFullAvatarUrl()
    {
        if (!empty($this->avatar_url)) {
            return self::AVATAR_URL . DIRECTORY_SEPARATOR . $this->avatar_url;
        } else {
            return self::AVATAR_URL . DIRECTORY_SEPARATOR . self::NO_AVATAR_FILENAME;
        }
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

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        // Updates a timestamp attribute to the current timestamp
        if (!$insert) {
            User::findIdentity($this->user_id)->touch('updated_at');
        }
    }

}
