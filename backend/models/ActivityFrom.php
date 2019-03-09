<?php
/**
 * Created by PhpStorm.
 * User: 31832
 * Date: 2019/3/9
 * Time: 10:32
 */
namespace  backend\models;
/*
 * 活动表单模型
 * */

use Yii;
use yii\base\Model;
use common\models\Activity;

class ActivityFrom extends Model
{
    const STATUS_UNAUDITED  = 0;//未审核状态
    const STATUS_APPROVED = 1;//已批准状态
    const STATUS_REJECTED= 2;//被驳回状态
    public $time_start_stamp;
    public $time_end_stamp;
    public $ticket_start_stamp;
    public $ticket_end_stamp;
    public $org_name;
    public $activity_name;
    public $category;
    public $introduction;
    public $location;
    public $max_people;
    public $status;
    public $current_serial;
    public function rules()
    {
        return
            [
                //要求必须存在值
                [
                    [
                        'activity_name',
                        'introduction' ,
                        'location',
                        'time_start_stamp',
                        'ticket_start_stamp',
                        'time_end_stamp',
                        'ticket_end_stamp',
                        'max_people',
                    ],
                    'required'
                ],
                [
                    //要求为整数
                    [
                        'category',
                        'status',
                        'max_people',
                        'current_serial',
                    ],
                    'integer'
                ],

                [['activity_name', 'introduction','location'], 'string','min'=>'2', 'max' => 255],
                ['activity_name', 'unique', 'targetClass' => '\common\models\Activity', 'message' => '这个名字已经被注册'],

                ['status', 'in', 'range' => [self::STATUS_UNAUDITED, self::STATUS_APPROVED,self::STATUS_REJECTED]],

                ['category', 'in', 'range' => [0,1]],

                ['category','default','value'=>'0'],
                ['status', 'default', 'value' => self::STATUS_UNAUDITED],
                ['time_start_stamp', 'compare','compareValue'=>date('Y-m-d H:i' , time()+7*3600), 'operator' => '>','message'=>'不能早于当前的时间'],
                ['ticket_start_stamp', 'compare','compareValue'=>date('Y-m-d H:i' , time()+7*3600), 'operator' => '>','message'=>'不能早于当前的时间'],
                ['time_end_stamp', 'compare','compareAttribute'=>'time_start_stamp', 'operator' => '>','message'=>'结束时间不能早于开始时间'],
                ['ticket_end_stamp', 'compare','compareAttribute'=>'ticket_start_stamp', 'operator' => '>','message'=>'结束时间不能早于开始时间'],

            ];
    }

    public function attributeLabels()
    {
        return [
            'activity_name' => '活动名字',
            'category' => '活动类别',
            'status' => '状态',
            'introduction' => '活动简介',
            'max_people' => '人数限制',
            'start_at' => '活动开始时间',
            'time_start_stamp' => '活动开始时间',
            'end_at' => '活动结束时间',
            'time_end_stamp' => '活动结束时间',
            'release_at' => '发布时间',
            'updated_at' => '上一次编辑时间',
            'ticketing_start_at' => '票务发布时间',
            'ticket_start_stamp' => '票务发布时间',
            'ticket_end_stamp' => '票务结束时间',
            'ticketing_end_at' => '票务结束时间',
            'current_serial'=>'票务序列号',
            'location'=>'活动地点',
        ];
    }

    public function create()
    {
        if (!$this->validate()) {
            return null;
        }

        /*$organizer->org_name = $this->org_name;
        $organizer->category=$this->category;
        $organizer->credential = $this->credential;
        $organizer->setPassword($this->password);
        $organizer->updated_at=$organizer->created_at=$organizer->signup_at=time()+7*3600;
        $organizer->generateAuthKey();//原理不明，保留就对了，据说是用于自动登录的*/
        $model = new Activity();
        $model->start_at=strtotime($this->time_start_stamp);
        $model->end_at=strtotime($this->time_end_stamp);
        $model->ticketing_start_at=strtotime($this->ticket_start_stamp);
        $model->ticketing_end_at=strtotime($this->ticket_end_stamp);
        $model->updated_at=$model->release_at=time()+7*3600;
        $model->current_people=0;
        $model->release_by=Yii::$app->user->identity->id;
        $model->status=self::STATUS_UNAUDITED;
        $model->location=$this->location;
        $model->introduction=$this->introduction;
        $model->max_people=$this->max_people;
        $model->current_serial=$this->current_serial;
        $model->activity_name=$this->activity_name;
        $model->category=$this->category;
        return $model->save() ? $model : null;
    }
}