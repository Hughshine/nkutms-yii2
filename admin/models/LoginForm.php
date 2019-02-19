<?php
namespace admin\models;

use Yii;
use yii\base\Model;
use admin\models\Admin;
/**
 * Login form
 */
class LoginForm extends Model
{
    public $admin_name;
    public $password;
    public $rememberMe = false;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // admin_name and password are both required
            [['admin_name', 'password'], 'required'],
            // rememberMe must be a boolean value
            // ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }
    public function attributeLabels()
    {
        return 
        [
            'admin_name'=>'用户名',
            'password'=>'密码',
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
                // if(!$user){
                //     $this->addError($attribute, 'no such admin');
                //     return;
                //     // exit;
                // }

                // if($user&&!$user->validatePassword($this->password)){
                // $this->addError($attribute, '密码或用户名不正确');
                //     return;
                // }
            }
            // $this->addError($attribute, 'zhengque');
            // return true;
        }
    }


    /**
     * Logs in a user using the provided admin_name and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login(Admin::findOne(["admin_name"=>$this->admin_name]),0);
            // return true;
        }
        // echo '1';
        return false;
    }

    /**
     * Finds user by [[admin_name]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Admin::findOne(["admin_name"=>$this->admin_name]);
        }

        return $this->_user;
    }
}
