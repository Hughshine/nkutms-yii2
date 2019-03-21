<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;


/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;
    public $credential;

    public $verifyCode;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email','credential'], 'trim'],
            [['email','credential'], 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => '邮箱尚未被注册应或该用户已被禁用'
            ],
            ['verifyCode', 'captcha'],
            ['credential', 'validateCredential',],
        ];
    }

    //验证邮箱与密码是否对应
    public function validateCredential($attribute, $params)
    {
        if (!$this->hasErrors())
        {
            $user=User::findByCredential($this->credential);
            if (!$user || strcmp($this->email,$user->email)!=0)
                $this->addError($attribute, '账号与邮箱不对应或账号已被禁用');
        }
    }

    public function attributeLabels()
    {
        return [
            'verifyCode' => '验证码',
            'credential' => '账号',
            'email' => '邮箱',
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }
        
        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();
    }
}
