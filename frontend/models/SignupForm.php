<?php
namespace frontend\models;

use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $user_name;
    public $admin_name;
    public $repassword;
    public $credential;
    public $verifyCode;
    //public $email;
    public $wechat_id;
    public $password;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['user_name', 'trim'],
            ['user_name', 'required'],
            ['user_name', 'unique', 'targetClass' => '\common\models\User', 'message' => '这个名字已经被注册'],
            ['user_name', 'string', 'min' => 2, 'max' => 255],

            ['wechat_id', 'trim'],
            ['wechat_id', 'required'],
            ['wechat_id', 'unique', 'targetClass' => '\common\models\User', 'message' => '这个微信id已经被注册'],
            ['wechat_id', 'string', 'min' => 2, 'max' => 255],

            ['credential', 'trim'],
            ['credential', 'required'],
            ['credential', 'unique', 'targetClass' => '\common\models\User', 'message' => '这个证书号已经被注册'],
            ['credential', 'string', 'min' => 2, 'max' => 255],

           /* ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => '这个邮箱已经被注册'],
            */
            ['repassword','compare','compareAttribute'=>'password','message'=>'密码和重复密码不相同'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['verifyCode', 'captcha'],
        ];
    }

    public function attributeLabels()
    {
        return
            [
                'user_name'=>'用户名',
                'password'=>'密码',
                'repassword'=>'重复密码',
                'wechat_id'=>'微信号',
                'rememberMe'=>'记住登录状态',
                'credential'=>'证书号',
                'verifyCode' => '验证码',
            ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->user_name = $this->user_name;
        $user->wechat_id = $this->wechat_id;
        $user->category=1;
        $user->credential=$this->credential;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }
}
