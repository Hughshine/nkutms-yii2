<?php
namespace frontend\models;

use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $admin_name;
    public $email;
    public $password;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['admin_name', 'trim'],
            ['admin_name', 'required'],
            ['admin_name', 'unique', 'targetClass' => '\common\models\Admin', 'message' => 'This admin_name has already been taken.'],
            ['admin_name', 'string', 'min' => 2, 'max' => 255],

            // ['email', 'trim'],
            // ['email', 'required'],
            // ['email', 'email'],
            // ['email', 'string', 'max' => 255],
            // ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
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
        $user->admin_name = $this->admin_name;
        // $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }
}
