<?php
namespace admin\models;

use yii\base\Model;
use admin\models\Organizer;

/**
 * Signup form
 */
class OrganizerUpdateForm extends Model
{
    public $org_name;
    public $category=0;
    public $status;
    public $org;

    /**
     * {@inheritdoc}
     */
    public function __construct($organizer) 
    {
        parent::__construct();
        $this->org_name=$organizer->org_name;
        $this->category=$organizer->category;
        $this->status=$organizer->status;
        $this->org=$organizer;
    }

    public function rules()
    {
        return [
            ['org_name', 'trim'],
            ['org_name', 'required'],
            ['org_name', 'unique', 'targetClass' => '\common\models\Organizer', 'message' => '这个名字已经被注册'],
            ['org_name', 'string', 'min' => 2, 'max' => 255],
            ['category','default','value'=>$this->org_name],

            ['category','required'],
            ['category','integer','min'=>0,'max'=>1],
            ['category','default','value'=>$this->category],

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
    public function update($organizer)
    {
        $changename=false;
        //在这做一个特殊处理暂时改变字符串，这样在改变名字的时候就不会违反名字的唯一键值特性，用一个变量记住是否修改
        if($this->org_name === $organizer->org_name)
        {
            $this->org_name='default_lyl'.$this->org_name;
        }
        else
        {
            $changename=true;
        }
        if (!$this->validate()) {
            return null;
        }
        if($changename)
        {
            $organizer->org_name = $this->org_name;
        }
        else
        {
            $this->org_name=$organizer->org_name;
        }
        $organizer->category=$this->category;
        $organizer->status=$this->status;
        return $organizer->save() ? $organizer : null;
    }
}
