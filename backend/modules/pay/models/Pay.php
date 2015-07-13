<?php

namespace backend\modules\pay\models;

use backend\modules\employment\models\PaidEmployment;
use backend\modules\group\models\Group;
use backend\modules\pay\Pay as Module;
use backend\modules\ticket\models\SeasonTicket;
use backend\modules\timetable\models\Timetable;
use backend\modules\user\models\User;
use Carbon\Carbon;
use common\models\Profile;
use Yii;

/**
 * This is the model class for table "{{%pay}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $ticket_id
 * @property string $comment
 * @property double $current_cost
 * @property double $cash
 * @property double $bonus_cash
 * @property string $create_at
 *
 * @property User $user
 * @property SeasonTicket $ticket
 */
class Pay extends \yii\db\ActiveRecord
{

    public $groups;
    public $startdate;
    /**
     * Максимальная сумма бонусного платежа. Задается для клиентской валидации.
     * Когда в контроллере определяется, для какого пользователя проводится платеж,
     * можно узнать баланс пользователя и задать его данной модели.
     * $model->maxBonusBalance = Profile::findOne(['user_id' => $id])->bonus_balance
     * @var float
     */
    public $maxBonusBalance = null;
    /**
     * свойство для статистики
     * @var float
     */
    public $sum_cash;
    /**
     * свойство для статистики
     * @var float
     */
    public $sum_bonus_cash;
    /**
     * свойство для статистики
     * @var int
     */
    public $count;
    /**
     * свойство для статистики
     * @var string
     */
    public $month;
    /**
     * свойство для статистики
     * @var string
     */
    public $date;
    private $_currentDate;
    private $_endDate;
    private $_ticketModel;
    private $_insertArray = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pay}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rulesArray = [
            [['user_id', 'startdate'], 'required'],
            [['user_id', 'ticket_id'], 'integer'],
            [['cash', 'bonus_cash'], 'number', 'min' => 0, 'max' => 100000000],
            [['create_at', 'current_cost', 'residual'], 'safe'],
            [['residual'], 'safe', 'on' => 'pay-create'],
            [['residual'], 'integer', 'integerOnly' => true, 'min' => 0, 'on' => 'pay-edit'],
            [['comment'], 'string', 'max' => 255],
            ['user_id', 'exist', 'targetAttribute' => 'user_id', 'targetClass' => Profile::className()],
            ['ticket_id', 'exist', 'targetAttribute' => 'id', 'targetClass' => SeasonTicket::className()],
            ['ticket_id', 'notEmptyBoth'],
            ['groups', 'in', 'range' => array_keys(Group::getGroupArray()), 'allowArray' => true],
            ['startdate', 'date', 'format' => 'php:d.m.Y'],
            [['cash', 'bonus_cash'], 'default', 'value' => '0.00'],
        ];

        if ($this->maxBonusBalance) {
            $rulesArray[] = ['bonus_cash', 'number', 'min' => 0, 'max' => $this->maxBonusBalance, 'tooBig' => Module::t('pay-admin', 'Insufficient funds in the bonus account')];
        } else {
            $rulesArray[] = ['bonus_cash', 'validateBonusCash'];
        }

        return $rulesArray;
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            'pay-create' => [
                'user_id',
                'startdate',
                'ticket_id',
                'cash',
                'bonus_cash',
                'comment',
                'groups',
                'startdate'
            ],
            'pay-edit' => ['residual']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => Module::t('pay-admin', 'User'),
            'ticket_id' => Module::t('pay-admin', 'Ticket'),
            'comment' => Module::t('pay-admin', 'Comment'),
            'current_cost' => Module::t('pay-admin', 'Price (at the time of payment)'),
            'cash' => Module::t('pay-admin', 'Paid money'),
            'bonus_cash' => Module::t('pay-admin', 'Paid bonuses'),
            'create_at' => Module::t('pay-admin', 'Time payment'),
            'groups' => Module::t('pay-admin', 'Groups'),
            'startdate' => Module::t('pay-admin', 'Subscription is valid from'),
            'residual' => Module::t('pay-admin', 'Balance training'),
            'userFullName' => Module::t('pay-admin', 'User'),
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
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTicket()
    {
        return $this->hasOne(SeasonTicket::className(), ['id' => 'ticket_id']);
    }

    public function getUserFullName()
    {
        return $this->profile->fullName;
    }

    public function afterValidate()
    {
        parent::afterValidate();
    }

    /**
     * Если указано значение $attribute то должно быть выбрано значение group
     * @param type $attribute
     * @param type $params
     */
    public function notEmptyBoth($attribute)
    {
        if (!empty($this->$attribute) && empty($this->groups)) {
            $this->addError('groups', Module::t('pay-admin', 'Please select at least one group'));
        }
    }

    /**
     * проверяет достаточно ли средств на бонусном балансе пользователя
     * если нет, то создает ошибку валидации
     * @param type $attribute
     * @param type $params
     */
    public function validateBonusCash($attribute)
    {
        $bonusBalance = Profile::findOne(['user_id' => $this->user_id])->bonus_balance;
        if ($bonusBalance < $this->$attribute) {
            $this->addError($attribute, Module::t('pay-admin', 'Insufficient funds in the bonus account'));
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $seasonTicket = SeasonTicket::findOne(['id' => $this->ticket_id]);
            $this->current_cost = $seasonTicket ? $seasonTicket->cost : 0;
            $profile = Profile::findOne(['user_id' => $this->user_id]);
            if ($insert) {
                //Проводим расчет с клиентом
                $difference = $this->cash + $this->bonus_cash - $this->current_cost;
                $profile->balance += $difference;
                $profile->bonus_balance -= $this->bonus_cash;

                $currentTime = Carbon::createFromTimestamp(time(), Yii::$app->timeZone);
                $this->create_at = $currentTime->toDateTimeString();
            }
            if (!$profile->save()) {
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * проверяет наличие аффлиата и при необходимости вызывает метод добавления бонусного баланса аффилиата
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $this->addPaidEmployment();
        $affiliateId = Profile::findOne(['user_id' => $this->user_id])->user_affiliate_id;

        if (null === $affiliateId) {
            return true;
        }

        if ($insert) {
            if ($this->cash > 0) {
                self::addAffiliatePercent($affiliateId, $this->cash);
            }
        }
    }

    private function addPaidEmployment()
    {
        if (!$this->ticket_id) {
            return true;
        }
        $this->_ticketModel = SeasonTicket::findOne(['id' => $this->ticket_id]);
        if (!$this->_ticketModel) {
            return true;
        }
        $this->_currentDate = Carbon::createFromFormat('d.m.Y', $this->startdate);
        $this->_endDate = self::getEmploymentEndDate($this->_currentDate, $this->_ticketModel->limit_format,
            $this->_ticketModel->limit_value);
        $timeTableGroups = Timetable::find()
            ->asArray()
            ->where(['group_id' => $this->groups])
            ->all();

        $cond = 'date >= :startdate AND pay.user_id = :user_id';
        $params = [':user_id' => $this->user_id, ':startdate' => $this->_currentDate->toDateString()];
        $existEmployments = PaidEmployment::find()
            ->select(['date', 'timetable_id', 'group_id' => 'timetable.group_id'])
            ->asArray()
            ->joinWith(['pay', 'timetable'])
            ->where($cond, $params)
            ->andWhere(['timetable.group_id' => $this->groups])
            ->all();
        $existLastEmployment = PaidEmployment::find()
            ->select(['date', 'timetable_id', 'group_id' => 'timetable.group_id'])
            ->asArray()
            ->joinWith(['pay', 'timetable'])
            ->where($cond, $params)
            ->andWhere(['timetable.group_id' => $this->groups])
            ->orderBy('date DESC')
            ->one();

        $this->addInsertArrayTimeLimit($timeTableGroups, $existEmployments, $this->_currentDate, $this->_endDate);
        if ($existLastEmployment) {
            $lastEmploymentDate = Carbon::createFromFormat('Y-m-d', $existLastEmployment['date'])->addDay();
            $this->_endDate = self::getEmploymentEndDate($lastEmploymentDate, $this->_ticketModel->limit_format,
                $this->_ticketModel->limit_value);
        }
        if ($this->_ticketModel->amount > 0) {
            $this->addInsertArrayAmountLimit($timeTableGroups, $existEmployments, $this->_currentDate, $this->_endDate);
        }
        if (!empty($this->_insertArray)) {
            $command = Yii::$app->db->createCommand()
                ->batchInsert(
                    '{{%paid_employment}}', ['date', 'pay_id', 'timetable_id'], $this->_insertArray
                );

            return is_int($command->execute());
        } else {
            return true;
        }
    }

    /**
     * прибавляет, к дате начала, заданный в абонементе период.
     * возвращает последий день оплаченного периода
     * @return obj Carbon\Carbon
     */
    protected static function getEmploymentEndDate($currentDate, $limit_format, $limit_value)
    {
        switch ($limit_format) {
            case 2:
                return Carbon::createFromTimestamp($currentDate->timestamp)->addWeeks($limit_value)->subDay();
            case 3:
                return Carbon::createFromTimestamp($currentDate->timestamp)->addMonths($limit_value)->subDay();
            case 4:
                return Carbon::createFromTimestamp($currentDate->timestamp)->addYears($limit_value)->subDay();
            default :
                return Carbon::createFromTimestamp($currentDate->timestamp)->addDays($limit_value)->subDay();
        }
    }

    /**
     * метод вызывается, если в абонементе не указано количество занятий
     * пробегает по рассписанию и добавляет в массив $_insertArray данные о новых оплаченных занятиях,
     * для вставки в базу данных.
     * Так же, если на период уже есть оплаченные занятия,
     * то обновляет ограничение по количеству занятий на количество уже оплаченных,
     * после чего, следует вызвать метод addInsertArrayAmountLimit(), чтоб он добавил в конец списка
     * занятия, согласно, количеству найденных оплаченных ранее занятий
     * @param array Timetable::find() $timeTableGroups
     * @param array PaidEmployment::find() $existEmployments
     * @param Carbon $currentDate
     * @param Carbon $endDate
     */
    private function addInsertArrayTimeLimit($timeTableGroups, $existEmployments, $currentDate, $endDate)
    {
        $findExist = 0;
        while ($endDate->timestamp >= $currentDate->timestamp) {
            foreach ($timeTableGroups as $tableRow) {
                if ($tableRow['week_day'] == $currentDate->dayOfWeek) {
                    $findFlag = false;
                    if (is_int($this->_ticketModel->amount) && $this->_ticketModel->amount < 1) {

                        goto amountEnd;
                    }
                    foreach ($existEmployments as $employmentRow) {
                        if (($employmentRow['date'] == $currentDate->toDateString())
                            && ($tableRow['id'] == $employmentRow['timetable_id'])
                        ) {
                            $findFlag = true;
                            ++$findExist;
                            break;
                        }
                    }
                    if (!$findFlag) {
                        $this->_insertArray[] = [
                            'date' => $currentDate->toDateString(),
                            'pay_id' => $this->id,
                            'timetable_id' => $tableRow['id'],
                        ];
                        --$this->_ticketModel->amount;
                    }
                }
            }

            $currentDate->addDay();
        }
        amountEnd:// goto amountEnd
        if (!is_int($this->_ticketModel->amount)) {
            $this->_ticketModel->amount += $findExist;
        }
    }

    /**
     * метод вызывается, если в абонементе указано количество занятий
     * пробегает по рассписанию и добавляет в массив $_insertArray данные о новых оплаченных занятиях,
     * для вставки в базу данных.
     * @param array Timetable::find() $timeTableGroups
     * @param array PaidEmployment::find() $existEmployments
     * @param Carbon $currentDate
     * @param Carbon $endDate
     */
    private function addInsertArrayAmountLimit($timeTableGroups, $existEmployments, $currentDate, $endDate)
    {
        while ($endDate->timestamp >= $currentDate->timestamp) {
            foreach ($timeTableGroups as $tableRow) {
                if ($tableRow['week_day'] == $currentDate->dayOfWeek) {
                    if ($this->_ticketModel->amount < 1) {
                        goto amountEnd;
                    }
                    $findFlag = false;
                    foreach ($existEmployments as $employmentRow) {
                        if (($employmentRow['date'] == $currentDate->toDateString()) &&
                            ($tableRow['id'] == $employmentRow['timetable_id'])
                        ) {
                            $findFlag = true;
                            break;
                        }
                    }
                    if (!$findFlag) {
                        $this->_insertArray[] = [
                            'date' => $currentDate->toDateString(),
                            'pay_id' => $this->id,
                            'timetable_id' => $tableRow['id'],
                        ];
                        --$this->_ticketModel->amount;
                    }
                }
            }
            $currentDate->addDay();
        }
        amountEnd:// goto amountEnd
    }

    /**
     * Зачисляет бонусные балы аффилиатам
     * если не требуется зачислений или операция прошла успешно, то возвращает true
     * иначе false
     * @param integer $affiliateId
     * @param float $cashOfPay
     * @return bool
     */
    public static function addAffiliatePercent($affiliateId, $cashOfPay)
    {
        if (!self::isActiveAffiliateProgram() || self::getMinSum() > $cashOfPay) {
            return true;
        }

        $model = Profile::findOne(['user_id' => $affiliateId]);
        if (!$model) {
            return true;
        }

        $percent = intval($cashOfPay * self::getDecimalPercent());
        $model->bonus_balance += $percent;
        if (!$model->save()) {
            return false;
        }
        // аффилиат второго уровня
        if (0 < self::getPercentSecondLevel() && 0 < $model->user_affiliate_id) {
            $modelSecondLevel = Profile::findOne(['user_id' => $model->user_affiliate_id]);
            if (!$modelSecondLevel) {
                return true;
            }

            $percentSecondLevel = intval($cashOfPay * self::getDecimalPercentSecondLevel());
            $modelSecondLevel->bonus_balance += $percentSecondLevel;
            if (!$modelSecondLevel->save()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Активна ли партнерка
     * @return bool
     */
    public static function isActiveAffiliateProgram()
    {
        return (bool)Yii::$app->config->get('AFFILIATE.IS_ACTIVE');
    }

    /**
     * минимальная сумма платежа при которой
     * разрешены партнерские отчисления
     * @return float
     */
    static function getMinSum()
    {
        return (float)Yii::$app->config->get('AFFILIATE.MIN_SUM');
    }

    /**
     * процент комиссии первого уровня - десятичная дробь (25% = 0.25)
     * @return float
     */
    static function getDecimalPercent()
    {

        return self::getPercent() / 100;
    }

    /**
     * Размер отчислений (процент) аффилиату первого уровня
     * @return int
     */
    static function getPercent()
    {
        return intval(Yii::$app->config->get('AFFILIATE.PERCENT'));
    }

    /**
     * Размер отчислений (процент) аффилиату второго уровня
     * @return int
     */
    static function getPercentSecondLevel()
    {
        return intval(Yii::$app->config->get('AFFILIATE.PERCENT_SECOND_LEVEL'));
    }

    /**
     * процент комиссии вторгого уровня - десятичная дробь (5% = 0.05)
     * @return float
     */
    static function getDecimalPercentSecondLevel()
    {
        return self::getPercentSecondLevel() / 100;
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            $profile = Profile::findOne(['user_id' => $this->user_id]);

            // возвращаем баланс и бонусы клиента на место
            $profile->balance -= $this->cash;
            $profile->balance -= $this->bonus_cash;
            $profile->balance += $this->current_cost;
            $profile->bonus_balance += $this->bonus_cash;
            if ($profile->save()) {

                /**
                 * проверяет наличие аффлиата и при необходимости вызывает метод удаления
                 * бонусного баланса аффилиата начисленного за удаляемый платеж
                 */
                if (null === $profile->user_affiliate_id) {
                    return true;
                }
                if ($this->cash > 0) {
                    self::deleteAffiliatePercent($profile->user_affiliate_id, $this->cash);
                }

                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Удаляет бонусные балы аффилиатов, при удалении (отмене) платежа
     * @param integer $affiliateId
     * @param float $cashOfPay
     * @return bool
     */
    public static function deleteAffiliatePercent($affiliateId, $cashOfPay)
    {
        if (!self::isActiveAffiliateProgram() || self::getMinSum() > $cashOfPay) {
            return true;
        }

        $model = Profile::findOne(['user_id' => $affiliateId]);
        if (!$model) {
            return true;
        }

        $percent = intval($cashOfPay * self::getDecimalPercent());
        $model->bonus_balance -= $percent;
        if (!$model->save()) {
            return false;
        }
        // аффилиат второго уровня
        if (0 < self::getPercentSecondLevel() && 0 < $model->user_affiliate_id) {
            $modelSecondLevel = Profile::findOne(['user_id' => $model->user_affiliate_id]);
            if (!$modelSecondLevel) {
                return true;
            }

            $percentSecondLevel = intval($cashOfPay * self::getDecimalPercentSecondLevel());
            $modelSecondLevel->bonus_balance -= $percentSecondLevel;
            if (!$modelSecondLevel->save()) {
                return false;
            }
        }

        return true;
    }

}
