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
use common\models\Activity;
use yii\db\ActiveRecord;
use common\models\Organizer;

class ActivityFrom extends ActiveRecord
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
    public $release_by;
    public $lastError;
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

    public static function tableName()
    {
        return '{{%tk_activity}}';
    }

    //关系名称
    public function getReleaseBy()
    {
        return $this->hasOne(Organizer::className(), ['id' => 'release_by']);
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

    //创建一个活动
    public function create()
    {
        if (!$this->validate()) {
            return null;
        }
        $model = new Activity();
        $transaction=Yii::$app->db->beginTransaction();
        try
        {
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
            $model->current_serial=1;
            $model->activity_name=$this->activity_name;
            $model->category=$this->category;

            if(!$model->save())
            throw new \Exception('活动发布失败!');

            /*
             * 此处可以写一个afterCreate方法来处理创建后事务
             * */
            $transaction->commit();
            return $model;
        }
        catch(\Exception $e)
        {
            $transaction->rollBack();
            $this->lastError=$e->getMessage();
            return null;
        }
    }

    //更新活动信息
    public function infoUpdate($activity)
    {
        $changeName=false;
        //在这做一个特殊处理暂时改变字符串，这样在改变名字的时候就不会违反名字的唯一键值特性，用一个变量记住是否修改
        if($this->activity_name === $activity->activity_name)
            $this->activity_name='prevent_rule_unique'.$this->activity_name;
        else
            $changeName=true;
        if (!$this->validate()) {
            if(!$changeName)
                $this->activity_name=$activity->activity_name;
            return null;
        }
        if($changeName)
            $activity->activity_name = $this->activity_name;
        else
            $this->activity_name=$activity->activity_name;

        $transaction=Yii::$app->db->beginTransaction();
        try
        {
            $activity->category=$this->category;
            $activity->status=$this->status;
            if($this->status==Activity::STATUS_APPROVED)
                $activity->release_at=time()+7*3600;
            $activity->location=$this->location;
            $activity->release_by=$this->release_by;
            $activity->current_serial=$this->current_serial;
            $activity->max_people=$this->max_people;
            $activity->introduction=$this->introduction;
            $activity->start_at=strtotime($this->time_start_stamp);
            $activity->end_at=strtotime($this->time_end_stamp);
            $activity->updated_at=time()+7*3600;
            $activity->ticketing_start_at=strtotime($this->ticket_start_stamp);
            $activity->ticketing_end_at=strtotime($this->ticket_end_stamp);
            $activity->current_people=0;
            $activity->current_serial=1;
            if(!$activity->save())
                throw new \Exception('活动修改失败!');
            /*
             * 此处可以写一个afterCreate方法来处理创建后事务
             * */
            $transaction->commit();
            return $activity;
        }
        catch(\Exception $e)
        {
            $transaction->rollBack();
            $this->lastError=$e->getMessage();
            return null;
        }
    }

    public function getList($cond,$curPage = 1,$pageSize = 5,$sortOrder=['id'=>SORT_DESC])
    {
        $model=new ActivityFrom();
        $select= ['id','activity_name','release_by','release_at',
                        'introduction','max_people','current_people',
                        'location','status','start_at','end_at',
                        'ticketing_start_at','ticketing_end_at'];
        $query=$model->find()
            ->select($select)
            ->where($cond)
            ->with('releaseBy')
            ->orderBy($sortOrder);
        //获取分页信息
        $res=$model->getPages($query,$curPage,$pageSize);
        return $res;
    }

    //获取分页数据
    public function getPages($query,$curPage=1,$pageSize=10,$search=null)
    {
        if($search)$query=$query->andFilterWhere($search);
        $data['count']=$query->count();
        if(!$data['count'])
            return ['count'=>0,'curPage'=>1,'pageSize'=>$pageSize,'start'=>0,'end'=>0,'data'=>[]];
        //防止页数超过总数
        $curPage=(ceil($data['count']/$pageSize)<$curPage)?
            ceil($data['count']/$pageSize):$curPage;
        $data['curPage']=$curPage;
        //每页显示条数
        $data['pageSize']=$pageSize;
        //起始页
        $data['start']=($curPage-1)*$pageSize+1;
        //末页
        $data['end']=(ceil($data['count']/$pageSize)==$curPage)?
            $data['count']:($curPage-1)*$pageSize+$pageSize;
        //取数据
        $data['data']=$query
            ->offset(($curPage-1)*$pageSize)
            ->limit($pageSize)
            ->asArray()->all();
        return $data;
    }

}