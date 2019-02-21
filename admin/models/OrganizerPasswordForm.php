<?php
namespace admin\models;

use Yii;
use yii\base\Model;
use admin\models\Organizer;


/*用于管理员修改组织者密码的表单
 *独立于update表单是因为有时候只需要修改密码，不用修改其他信息。
 * 由于是管理员来修改，所以并不需要验证
 * */
class OrganizerPasswordForm extends Model
{
    public $password;
    public $repassword;
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
            [['password','repassword'], 'string', 'min' => 6],
            [['password','repassword'], 'required'],
            //重复密码必须与密码相等
            ['repassword','compare','compareAttribute'=>'password','message'=>'密码和重复密码不相同'],
        ];
    }

    public function attributeLabels()
    {
        return 
        [
            'password'=>'密码',
            'repassword'=>'重复密码',
        ];
    }

    //向数据库提交修改的密码
    public function repassword($organizer)
    {
        if (!$this->validate())
            return null;
        $organizer = $this->org;
        $organizer->setPassword($this->password);
        //由于之前用了validate方法，所以此次save用false
        return $organizer->save(false);
    }
}
