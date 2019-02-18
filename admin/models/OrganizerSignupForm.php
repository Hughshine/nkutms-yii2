<?php
namespace admin\models;

use yii\base\Model;
use admin\models\Organizer;

/**
 * Signup form
 */
class OrganizerSignupForm extends Model
{
    public $id;
    public $org_name;
    public $wechat_id;
    public $category=0;
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


            ['wechat_id', 'trim'],
            ['wechat_id', 'required'],
            ['wechat_id', 'string', 'max' => 255],
            ['wechat_id', 'unique', 'targetClass' => '\common\models\Organizer', 'message' => '这个微信id已经被注册'],
            ['wechat_id','default','value'=>$this->status],


            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['password','default','value'=>$this->password],


            ['category','required'],
            ['category','integer','min'=>0,'max'=>1],

            ['status','required'],
            ['status','default','value'=>$this->status],
            ];
    }

    public function attributeLabels()
    {
        return [
        'org_name'=>'组织者名字',
        'status'=>'状态',
        'time_release'=>'注册时间',
        'wechat_id'=>'微信号',
        'activity_total'=>'活动总数',
        'category'=>'分类',
        'auth_key'=>'自动登录密码',
        'password'=>'密码',
        'password_reset_token'=>'重置密码token',
        'updated_time'=>'更新时间',
        'access_token'=>'小程序请求发送',
        ];
    }

    /**
     * Signs organizer up.
     *
     * @return organizer|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $organizer = new Organizer();
        $organizer->org_name = $this->org_name;
        $organizer->category=$this->category;
        $organizer->wechat_id = $this->wechat_id;
        $organizer->setPassword($this->password);
        $organizer->generateAuthKey();        
        return $organizer->save() ? $organizer : null;
    }

}