<?php
namespace admin\models;

use Yii;
use yii\base\Model;
use admin\models\Organizer;


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
