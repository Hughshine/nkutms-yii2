<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

class LoginForm extends Model
{
    public $credential;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // user_name and password are both required
            [['credential', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
             ['password', 'validatePassword'],
        ];
    }
    public function attributeLabels()
    {
        return 
        [
            'credential'=>'账号',
            'password'=>'密码',
            'rememberMe'=>'记住登录状态',
        ];
    }
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '密码或用户名不正确');
            }
        }
    }


    /**
     * Logs in a user using the provided user_name and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[user_name]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findOne(["credential"=>$this->credential]);
        }

        return $this->_user;
    }
}
