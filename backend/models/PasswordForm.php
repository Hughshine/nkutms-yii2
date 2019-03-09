<?php
namespace backend\models;

use Yii;
use yii\base\Model;


/*用于修改密码的表单
 *独立于update表单是因为有时候只需要修改密码，不用修改其他信息。
 * 由于是自己修改，所以并不需要验证
 * */
class PasswordForm extends Model
{
    public $password;
    public $rePassword;
    public $oldPassword;
    public $org;

    /**
     * {@inheritdoc}
     */
    public function __construct($organizer)
    {
        parent::__construct();
        $this->org=$organizer;
    }

    public function rules()
    {
        return 
        [
            [['password','rePassword'], 'string', 'min' => 6],
            [['password','rePassword','oldPassword'], 'required'],
            //重复密码必须与密码相等
            ['rePassword','compare','compareAttribute'=>'password','message'=>'密码和重复密码不相同'],
            ['oldPassword', 'validatePassword'],
        ];
    }
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!$this->org || !$this->org->validatePassword($this->oldPassword)) {
                $this->addError($attribute, '旧密码不正确');
            }
        }
    }

    public function attributeLabels()
    {
        return 
        [
            'password'=>'密码',
            'rePassword'=>'重复密码',
            'oldPassword'=>'旧密码',
        ];
    }

    //向数据库提交修改的密码
    public function rePassword()
    {
        if (!$this->validate())
            return null;
        $this->org->setPassword($this->password);
        //由于之前用了validate方法，所以此次save用false
        //在此save会更新updated_at字段
        return $this->org->save(false);
    }
}
