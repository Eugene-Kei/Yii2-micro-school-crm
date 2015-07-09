<?php

namespace backend\modules\ticket\models;

use yii\helpers\ArrayHelper;
use backend\modules\ticket\Ticket;

/**
 * This is the model class for table "{{%season_ticket}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $amount
 * @property integer $limit_format
 * @property integer $limit_value
 */
class SeasonTicket extends \yii\db\ActiveRecord {

    /** limit formats status */
    const LIMIT_FORMAT_DAY   = 1;
    const LIMIT_FORMAT_WEEK  = 2;
    const LIMIT_FORMAT_MONTH = 3;

    public static function getLimitFormatArray() {
        return [
            self::LIMIT_FORMAT_DAY   => Ticket::t('ticket', 'Days'),
            self::LIMIT_FORMAT_WEEK  => Ticket::t('ticket', 'Weeks'),
            self::LIMIT_FORMAT_MONTH => Ticket::t('ticket', 'Months')
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%season_ticket}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['title', 'limit_format', 'limit_value'], 'required'],
            [['amount'], 'integer', 'min' => 1],
            [['limit_format', 'limit_value'], 'integer', 'min' => 1],
            ['limit_format', 'in', 'range' => array_keys(self::getLimitFormatArray()), 'allowArray' => true],
            [['title'], 'string', 'max' => 80],
            [['cost'], 'double', 'min' => 0.00, 'max' => 100000000.00],
            [['description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id'           => Ticket::t('ticket', 'ID'),
            'title'        => Ticket::t('ticket', 'Title'),
            'description'  => Ticket::t('ticket', 'Description'),
            'cost'         => Ticket::t('ticket', 'Cost'),
            'amount'       => Ticket::t('ticket', 'Number of lessons'),
            'limit_format' => Ticket::t('ticket', 'Limit - format'),
            'limit_value'  => Ticket::t('ticket', 'Limit - value'),
        ];
    }

    /**
     * Массив ['id'=>'title'] всех абонементов
     * @return type
     */
    static function getTicketArray() {
        return ArrayHelper::map(self::find()->asArray()->select(['id, CONCAT_WS(" - ", title, cost) as name'])
                                ->orderBy('title')
                                ->all(), 'id', 'name');
    }

    public function getAmount() {
        return $this->amount;
    }

}
