<?php
namespace frontend\models;

use common\models\Profile;
use common\models\User;
use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $name;
    public $phone;
    public $email;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'phone', 'name'], 'filter', 'filter' => 'trim'],
            [['phone', 'name', 'email'], 'required'],
            [
                'phone',
                'unique',
                'targetClass' => '\common\models\User',
                'message' => Yii::t('app', 'This phone has already been taken.')
            ],
            ['phone', 'match', 'pattern' => User::$patternPhone],
            ['name', 'string', 'max' => 50],
            ['name', 'match', 'pattern' => Profile::$patternName],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            [
                'email',
                'unique',
                'targetClass' => '\common\models\User',
                'message' => Yii::t('app', 'This email address has already been taken.')
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'phone' => Yii::t('app', 'Phone'),
            'email' => Yii::t('app', 'Email'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * Signs user up.
     *
     * @return true|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->phone = $this->phone;
            $user->email = $this->email;
            $randLength = mt_rand(6, 9);
            $this->password = Yii::$app->security->generateRandomString($randLength);
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
                $profile = new Profile();
                $profile->user_id = $user->id;
                $profile->name = $this->name;

                //если в куках есть id аффилиата, сохраняем его
                $affiliateId = (int) Yii::$app->request->cookies['affiliate'];
                if ($affiliateId > 0 && User::findIdentity($affiliateId)) {
                    $profile->user_affiliate_id = $affiliateId;
                }

                $profile->save();

                return $this->sendRegistrationEmail();
            }
        }

        return null;
    }

    public function sendRegistrationEmail()
    {
        $layouts = ['html' => 'registration-html', 'text' => 'registration-text'];
        $params = ['phone' => $this->phone, 'password' => $this->password];

        return Yii::$app->mailer->compose($layouts, $params)
            ->setFrom([\Yii::$app->params['supportEmail'] => Yii::$app->config->get('CONTACT.ORGANIZATION_NAME') . ' robot'])
            ->setTo($this->email)
            ->setSubject(Yii::$app->config->get('CONTACT.ORGANIZATION_NAME') . ' :: ' . Yii::t('app', 'Your Account Info'))
            ->send();
    }
}