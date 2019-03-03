<?php
namespace admin\models;

use yii\base\Model;
use common\models\Organizer;

class OrganizerSignupForm extends Model
{
    public $id;
    public $org_name;
    public $category=0;
    public $credential;
    public $password;
    public $status=10;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['org_name', 'trim'],
            ['org_name', 'required'],
            ['org_name', 'unique', 'targetClass' => '\common\models\Organizer', 'message' => '这个名字已经被注册'],
            ['org_name', 'string', 'min' => 2, 'max' => 255],
            ['org_name','default','value'=>$this->status],


            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['password','default','value'=>$this->password],


            ['category','required'],
            ['category','integer','min'=>0,'max'=>1],

            ['status','required'],
            ['status','default','value'=>$this->status],

            ['credential','required'],
            ['credential','integer'],
            ['credential', 'unique', 'targetClass' => '\common\models\Organizer', 'message' => '这个证书号已经被注册'],
            ];
    }

    public function attributeLabels()
    {
        return [
        'org_name'=>'组织者名字',
        'status'=>'状态',
        'credential'=>'证书号',
        'category'=>'分类',
        'password'=>'密码',
        ];
    }

    /*
     * 创建一个组织者
     * */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $organizer = new Organizer();
        $organizer->org_name = $this->org_name;
        $organizer->category=$this->category;
        $organizer->credential = $this->credential;
        $organizer->setPassword($this->password);
        $organizer->updated_at=$organizer->created_at=$organizer->signup_at=time()+7*3600;
        $organizer->generateAuthKey();//原理不明，保留就对了，据说是用于自动登录的
        return $organizer->save() ? $organizer : null;
    }

}
