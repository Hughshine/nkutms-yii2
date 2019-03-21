<?php
/**
 * Created by PhpStorm.
 * User: 31832
 * Date: 2019/3/9
 * Time: 10:32
 */
namespace  common\models;

use Yii;
use yii\db\ActiveRecord;

/*
 * 活动表单
 * */
class ActivityForm extends ActiveRecord//因为要查询,所以继承ActiveRecord
{
    public $activity_name;
    public $act_id;
    public $category;
    public $current_serial;
    public $introduction;
    public $location;
    public $max_people;
    public $release_by;
    public $status;
    public $ticket_start_stamp;//字符串格式,在创建和更新动作那会转为整数
    public $ticket_end_stamp;//字符串格式
    public $time_start_stamp;//字符串格式
    public $time_end_stamp;//字符串格式

    public $org_name;//用于查找发布者名字

    public $lastError;//用于存放最后一次异常信息

    public function rules()
    {
        return
            [
                //要求必须存在值
                [
                    [
                        'activity_name',
                        'introduction' ,
                        'release_by',
                        'location',
                        'category',
                        'time_start_stamp',
                        'ticket_start_stamp',
                        'time_end_stamp',
                        'ticket_end_stamp',
                        'max_people',
                        'status',
                    ],
                    'required',
                    'on'=>['Create','Update']//对于这两个场景有效
                ],
                [
                    //要求为整数
                    [
                        'status',
                        'max_people',
                        'current_serial',
                    ],
                    'integer',
                    'on'=>['Create','Update','Review']
                ],

                [['activity_name'], 'string', 'max' => 32,'on'=>['Create','Update','Review']],

                [['location'], 'string', 'max' => 64,'on'=>['Create','Update','Review']],

                [['introduction',], 'string', 'max' => 255,'on'=>['Create','Update','Review']],

                ['status', 'in', 'range' => [Activity::STATUS_UNAUDITED, Activity::STATUS_APPROVED,Activity::STATUS_REJECTED],'on'=>['Create','Update','Review']],

                [
                    'category', 'compare',
                    'compareValue'=>0,
                    'operator' => '>=','message'=>'活动分类无效',
                    'on'=>['Create','Update',]
                ],
                [
                    'category', 'compare',
                    'compareValue'=>count(ACT_CATEGORY),
                    'operator' => '<','message'=>'活动分类无效',
                    'on'=>['Create','Update',]
                ],

                ['category','default','value'=>'0','on'=>'Create',],
                ['current_people','default','value'=>'0','on'=>'Create',],
                ['status', 'default', 'value' => Activity::STATUS_UNAUDITED,'on'=>'Create',],
                //['org_name','safe'],
                [
                    'time_start_stamp', 'compare',
                    'compareValue'=>date('Y-m-d H:i:s' , time()+7*3600),
                    'operator' => '>','message'=>'不能早于当前的时间',
                    'on'=>'Create',
                ],
                [
                    'ticket_start_stamp', 'compare',
                    'compareValue'=>date('Y-m-d H:i:s' , time()+7*3600),
                    'operator' => '>','message'=>'不能早于当前的时间',
                    'on'=>'Create',
                ],
                [
                    'time_end_stamp', 'compare',
                    'compareAttribute'=>'time_start_stamp',
                    'operator' => '>','message'=>'结束时间不能早于开始时间',
                    'on'=>['Create','Update',],
                ],
                [
                    'ticket_end_stamp',
                    'compare','compareAttribute'=>
                    'ticket_start_stamp', 'operator' => '>',
                    'message'=>'结束时间不能早于开始时间',
                    'on'=>['Create','Update',],
                ],
                [
                    'time_start_stamp', 'compare',
                    'compareAttribute'=>'ticket_end_stamp', 'operator' => '>',
                    'message'=>'活动开始时间不能早于票务结束时间',
                    'on'=>['Create','Update',]
                ],
                //外键要求
                [
                    ['release_by'], 'exist', 'skipOnError' => false,
                    'targetClass' => 'common\models\Organizer',
                    'targetAttribute' => ['release_by' => 'id'],
                    'on'=>['Create','Update','Review'],
                ],

            ];
    }
    //设置场景值
    public function scenarios()
    {
        return [
            'Create' =>//表示某个场景所用到的信息,没标记出来的不会有影响
                [
                    'activity_name', 'release_by', 'category',
                    'status','location','release_at',
                    'ticket_start_at', 'ticket_end_at',
                    'start_at','end_at',
                    'ticket_start_stamp', 'ticket_end_stamp',
                    'time_start_stamp','time_end_stamp',
                    'introduction', 'max_people',
                ],
            'Update' =>
                [
                    'activity_name', 'release_by', 'category',
                    'status','location','release_at',
                    'ticket_start_at', 'ticket_end_at',
                    'start_at','end_at',
                    'ticket_start_stamp', 'ticket_end_stamp',
                    'time_start_stamp','time_end_stamp',
                    'introduction', 'max_people',
                ],
            'Review'=>['status','release_at'],
            'default'=>
                [
                    'activity_name', 'release_by', 'category',
                    'status','location','release_at',
                    'ticket_start_at', 'ticket_end_at',
                    'start_at','end_at',
                    'ticket_start_stamp', 'ticket_end_stamp',
                    'time_start_stamp','time_end_stamp',
                    'introduction', 'max_people',
                ],
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
            'release_by'=>'发布者ID',
        ];
    }

    //以当前表单的信息创建一个活动,返回这个活动的模型
    /*
     * 必须字段为:
     * time_start_stamp,time_end_stamp
     * ticket_start_stamp,ticket_end_stamp
     * release_by,location,introduction,
     * max_people,activity_name,category
     * */
    public function create()
    {
        $this->scenario='Create';
        $transaction=Yii::$app->db->beginTransaction();
        try
        {
            if(!$this->validate())throw new \Exception('数据不符合要求!');
            $model = new Activity();
            $model->start_at=strtotime($this->time_start_stamp);
            $model->end_at=strtotime($this->time_end_stamp);
            $model->ticketing_start_at=strtotime($this->ticket_start_stamp);
            $model->ticketing_end_at=strtotime($this->ticket_end_stamp);
            $model->release_at=time()+7*3600;
            $model->current_people=0;
            $model->release_by=$this->release_by;
            $model->status=Activity::STATUS_UNAUDITED;
            $model->location=$this->location;
            $model->introduction=$this->introduction;
            $model->max_people=$this->max_people;
            $model->current_serial=1;
            $model->activity_name=$this->activity_name;
            $model->category=$this->category;
            
            if(!$model->save())throw new \Exception('活动发布失败!');

             //此处可以写一个afterCreate方法来处理创建后事务

            $transaction->commit();
            return $model;
        }
        catch(\Exception $e)
        {
            $transaction->rollBack();
            $this->lastError=$e->getMessage();
            Yii::$app->getSession()->setFlash('error', $this->lastError);
            return null;
        }
    }

    //以当前表单的信息更新$activity活动信息,返回是否成功
    //之所以不用update做名字,因为父类有update方法,不想重写
    /*
     * 必须字段为:
     * time_start_stamp,time_end_stamp
     * ticket_start_stamp,ticket_end_stamp
     * release_by,location,introduction,
     * max_people,activity_name,category,
     * status
     * */
    public function infoUpdate($model)
    {
        $this->scenario='Update';
        $transaction=Yii::$app->db->beginTransaction();
        try
        {
            if (!$this->validate())throw new \Exception('更新信息需要调整');
            $model->activity_name = $this->activity_name;
            
            $model->status=$this->status;
            if($this->status==Activity::STATUS_APPROVED)
                $model->release_at=time()+7*3600;
            $model->location=$this->location;
            $model->release_by=$this->release_by;
            $model->max_people=$this->max_people;
            $model->introduction=$this->introduction;
            $model->start_at=strtotime($this->time_start_stamp);
            $model->end_at=strtotime($this->time_end_stamp);
            $model->updated_at=time()+7*3600;
            $model->ticketing_start_at=strtotime($this->ticket_start_stamp);
            $model->ticketing_end_at=strtotime($this->ticket_end_stamp);
            $model->category=$this->category;
            if(!$model->save()) throw new \Exception('活动修改失败!');

            //此处可以写一个afterCreate方法来处理创建后事务

            $transaction->commit();
            return true;
        }
        catch(\Exception $e)
        {
            $transaction->rollBack();
            $this->lastError=$e->getMessage();
            Yii::$app->getSession()->setFlash('error', $this->lastError);
            return false;
        }
    }


    //一键审核功能实现将$model的status置为$this->status,返回是否成功
    //必须字段为status
    public function review($model)
    {
        $this->scenario='Review';
        $transaction=Yii::$app->db->beginTransaction();
        try
        {
            $model->status=$this->status;
            if($this->status==Activity::STATUS_APPROVED)
                $model->release_at=time()+7*3600;
            if(!$model->save()) throw new \Exception('非法状态修改失败!');

            $transaction->commit();
            return true;
        }
        catch(\Exception $e)
        {
            $transaction->rollBack();
            $this->lastError=$e->getMessage();
            Yii::$app->getSession()->setFlash('error', $this->lastError);
            return false;
        }
    }

    //查询结果并返回成列表
    public static function getList($cond,$curPage = 1,$pageSize = 5,$sortOrder=['id'=>SORT_DESC])
    {
        $model=new ActivityForm();
        $select= ['id','activity_name','release_by','release_at',
                        'introduction','max_people','current_people',
                        'location','status','start_at','end_at',
                        'ticketing_start_at','ticketing_end_at'];
        $query=$model->find()
            ->select($select)
            ->where($cond)
            ->with('releaseBy')//根据关系releaseBy
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