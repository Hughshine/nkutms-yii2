<?php
namespace admin\models;

use Yii;
use yii\base\Model;
use admin\models\Admin;
use common\models\Organizer;


/*用于管理员修改密码的表单
 *独立于update表单是因为有时候只需要修改密码，不用修改其他信息。
 * 由于是管理员来修改，所以并不需要验证
 * */
class AdminPasswordForm extends Model
{
    public $password;
    public $repassword;
    public $oldpassword;
    public $admin;

    /**
     * {@inheritdoc}
     */
    public function __construct($adm)
    {
        parent::__construct();
        $this->admin=$adm;
    }

    public function rules()
    {
        return 
        [
            [['password','repassword'], 'string', 'min' => 6],
            [['password','repassword','oldpassword'], 'required'],
            //重复密码必须与密码相等
            ['repassword','compare','compareAttribute'=>'password','message'=>'密码和重复密码不相同'],
            ['oldpassword', 'validatePassword'],
        ];
    }
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!$this->admin || !$this->admin->validatePassword($this->oldpassword)) {
                $this->addError($attribute, '旧密码不正确');
            }
        }
    }

    public function attributeLabels()
    {
        return 
        [
            'password'=>'密码',
            'repassword'=>'重复密码',
            'oldpassword'=>'旧密码',
        ];
    }

    //向数据库提交修改的密码
    public function repassword($adm)
    {
        if (!$this->validate())
            return null;
        $adm = $this->admin;
        $adm->setPassword($this->password);
        //由于之前用了validate方法，所以此次save用false
        //在此save会更新updated_at字段
        return $adm->save(false);
    }
}
