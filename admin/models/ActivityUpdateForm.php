<?php
namespace admin\models;

use yii\base\Model;
use admin\models\TkActivity;

class ActivityUpdateForm extends Model
{
    const STATUS_UNAUDITED  = 0;//未审核状态
    const STATUS_APPROVED = 1;//已批准状态
    const STATUS_REJECTED= 2;//被驳回状态


    public $activity_name;
    public $category;
    public $status;
    public $introduction;
    public $time_start_stamp;
    public $time_end_stamp;
    public $ticket_start_stamp;
    public $ticket_end_stamp;
    public $location;
    public $id;
    public $max_people;
    public $current_serial;
    public $act;

    /**
     * {@inheritdoc}
     */
    public function __construct($activity)
    {
        parent::__construct();
        //以下变量用于在view\_updateform中赋默认值
        $this->activity_name=$activity->activity_name;
        $this->category=$activity->category;
        $this->introduction=$activity->introduction;
        $this->location=$activity->location;
        $this->status=$activity->status;
        $this->time_start_stamp=date('Y-m-d H:i' , $activity->start_at);
        $this->time_end_stamp=date('Y-m-d H:i' , $activity->end_at);
        $this->ticket_start_stamp=date('Y-m-d H:i' , $activity->ticketing_start_at);
        $this->ticket_end_stamp=date('Y-m-d H:i' , $activity->ticketing_end_at);
        $this->id=$activity->release_by;
        $this->max_people=$activity->max_people;
        $this->current_serial=$activity->current_serial;
        //记录这个表单对应的活动
        $this->act=$activity;
    }

    public function rules()
    {
        return [//要求必须存在值
            [
                [
                    'activity_name',
                    'introduction',
                    'id',
                    'time_start_stamp',
                    'time_end_stamp',
                    'ticket_start_stamp',
                    'ticket_end_stamp',
                    'max_people',
                    'current_serial',
                ], 
                'required'
            ],
            //要求为整数
            [
                [
                    'max_people',
                    'current_serial',
                    'id',
                ], 
                'integer'
            ],
            //唯一性要求
            ['activity_name', 'unique', 'targetClass' => '\admin\models\TkActivity', 'message' => '这个名字已经被注册'],

            //格式要求
            [['activity_name', 'introduction','location'], 'string','min'=>'2', 'max' => 255],

            //默认值初始化
            ['category','default','value'=>$this->category],
            ['activity_name','default','value'=>$this->activity_name],
            ['status','default','value'=>$this->status],
            ['time_start_stamp','default','value'=>$this->time_start_stamp],
            ['time_end_stamp','default','value'=>$this->time_end_stamp],
            ['ticket_start_stamp','default','value'=>$this->ticket_start_stamp],
            ['ticket_end_stamp','default','value'=>$this->ticket_end_stamp],
            ['id','default','value'=>$this->id],

            //比较要求
            ['time_start_stamp', 'compare','compareValue'=>date('Y-m-d H:i' , time()+7*3600), 'operator' => '>','message'=>'不能早于当前的时间'],
            ['ticket_start_stamp', 'compare','compareValue'=>date('Y-m-d H:i' , time()+7*3600), 'operator' => '>','message'=>'不能早于当前的时间'],
            ['time_end_stamp', 'compare','compareAttribute'=>'time_start_stamp', 'operator' => '>','message'=>'结束时间不能早于开始时间'],
            ['ticket_end_stamp', 'compare','compareAttribute'=>'ticket_start_stamp', 'operator' => '>','message'=>'结束时间不能早于开始时间'],

            //范围要求
            ['status', 'in', 'range' => [self::STATUS_UNAUDITED, self::STATUS_APPROVED,self::STATUS_REJECTED]],
            ['category', 'in', 'range' => [0,1]],

            //其他要求
            ['activity_name', 'trim'],//目前还不知道这个是干啥的

            ['id', 'exist', 'targetClass' => 'admin\models\Organizer', 'message' => '该组织者不存在'],

            ];


    }

    public function attributeLabels()//用于参数的翻译
    {
        return [
        'activity_name'=>'活动名字',
        'status'=>'状态',
        'id'=>'发布者ID',
        'introduction'=>'活动介绍',
        'category'=>'活动分类',
        'time_start_stamp'=>'活动开始时间',
        'time_end_stamp'=>'活动结束时间',
        'ticket_start_stamp'=>'票务开放时间',
        'ticket_end_stamp'=>'票务结束时间',
        'max_people'=>'人数限制',
        'location'=>'活动地点',
        'current_serial'=>'票务序列号',
        ];
    }


    //更新活动信息
    public function update($activity)
    {
        $changename=false;
        //在这做一个特殊处理暂时改变字符串，这样在改变名字的时候就不会违反名字的唯一键值特性，用一个变量记住是否修改
        if($this->activity_name === $activity->activity_name)
        {
            $this->activity_name='default_lyl'.$this->activity_name;
        }
        else
        {
            $changename=true;
        }
        if (!$this->validate()) {
            if(!$changename)
            {
                $this->activity_name=$activity->activity_name;
            }
            return null;
        }
        if($changename)
        {
            $activity->activity_name = $this->activity_name;
        }
        else
        {
            $this->activity_name=$activity->activity_name;
        }

        $activity->category=$this->category;
        $activity->status=$this->status;
        $activity->location=$this->location;
        $activity->release_by=$this->id;
        $activity->current_serial=$this->current_serial;
        $activity->max_people=$this->max_people;
        $activity->introduction=$this->introduction;
        $activity->start_at=strtotime($this->time_start_stamp);
        $activity->end_at=strtotime($this->time_end_stamp);
        $activity->updated_at=time()+7*3600;
        $activity->ticketing_start_at=strtotime($this->ticket_start_stamp);
        $activity->ticketing_end_at=strtotime($this->ticket_end_stamp);

        return $activity->save(false) ? $activity : null;
    }
}
