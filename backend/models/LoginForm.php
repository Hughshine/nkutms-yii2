<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\Organizer;

class LoginForm extends Model
{
    public $org_name;
    public $password;
    public $rememberMe = true;

    private $org;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // user_name and password are both required
            [['org_name', 'password'], 'required'],
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
            'org_name'=>'用户名',
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
     * Finds user by [[org_name]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->org === null) {
            $this->org = Organizer::findOne(["org_name"=>$this->org_name]);
        }
        return $this->org;
    }
}
