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
use yii\db\Exception;

/*
 * 活动表单
 * */
class ActivityForm extends ActiveRecord//因为要查询,所以继承ActiveRecord
{
    public $activity_name;
    public $act_id;
    public $category;
    public $current_serial;
    public $current_people;
    public $introduction;
    public $location;
    public $max_people;
    public $release_by;
    public $status;
    public $pic_url;//图片
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
                    'on'=>['Create','Update','default',]//对于这些场景有效
                ],
                [
                    //要求为整数
                    [
                        'status',
                        'max_people',
                        'current_serial',
                    ],
                    'integer',
                    'on'=>['Create','Update','Review','default',]
                ],

                [['activity_name'], 'string', 'max' => 32,'on'=>['Create','Update','Review','default',]],

                [['location'], 'string', 'max' => 64,'on'=>['Create','Update','Review','default',]],

                ['status', 'in', 'range' => [Activity::STATUS_UNAUDITED, Activity::STATUS_APPROVED,Activity::STATUS_REJECTED],'on'=>['Create','Update','Review','default',]],

                [
                    'category', 'compare',
                    'compareValue'=>0,
                    'operator' => '>=','message'=>'活动分类无效',
                    'on'=>['Create','Update','default',]
                ],
                [
                    'category', 'compare',
                    'compareValue'=>count(ACT_CATEGORY),
                    'operator' => '<','message'=>'活动分类无效',
                    'on'=>['Create','Update','default',]
                ],

                ['category','default','value'=>'0','on'=>['Create','default',]],
                ['current_people','default','value'=>'0','on'=>'Create',],
                ['status', 'default', 'value' => Activity::STATUS_UNAUDITED,'on'=>['Create','default',]],
                //['org_name','safe'],
                [
                    'time_start_stamp', 'compare',
                    'compareValue'=>date('Y-m-d H:i:s' , time()+7*3600),
                    'operator' => '>','message'=>'不能早于当前的时间',
                    'on'=>['Create','default',]
                ],
                [
                    'ticket_start_stamp', 'compare',
                    'compareValue'=>date('Y-m-d H:i:s' , time()+7*3600),
                    'operator' => '>','message'=>'不能早于当前的时间',
                    'on'=>['Create','default',]
                ],
                [
                    'time_end_stamp', 'compare',
                    'compareAttribute'=>'time_start_stamp',
                    'operator' => '>','message'=>'结束时间不能早于开始时间',
                    'on'=>['Create','Update','default',],
                ],
                [
                    'ticket_end_stamp',
                    'compare','compareAttribute'=>
                    'ticket_start_stamp', 'operator' => '>',
                    'message'=>'结束时间不能早于开始时间',
                    'on'=>['Create','Update','default',],
                ],
                [
                    'time_start_stamp', 'compare',
                    'compareAttribute'=>'ticket_end_stamp', 'operator' => '>',
                    'message'=>'活动开始时间不能早于票务结束时间',
                    'on'=>['Create','Update','default',]
                ],
                //外键要求
                [
                    ['release_by'], 'exist', 'skipOnError' => false,
                    'targetClass' => 'common\models\Organizer',
                    'targetAttribute' => ['release_by' => 'id'],
                    'on'=>['Create','Update','Review','default',],
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
                    'introduction', 'max_people','pic_url',
                    'updated_at','created_at',
                ],
            'Update' =>
                [
                    'activity_name', 'release_by', 'category',
                    'status','location','release_at',
                    'ticket_start_at', 'ticket_end_at',
                    'start_at','end_at',
                    'ticket_start_stamp', 'ticket_end_stamp',
                    'time_start_stamp','time_end_stamp',
                    'introduction', 'max_people','updated_at',
                ],
            'ChangeStatus'=>['status','release_at','updated_at'],
            'ChangePicture'=>['pic_url','updated_at'],
            'ChangeSerial'=>['current_serial','current_people','updated_at'],
            'default'=>
                [
                    'activity_name', 'release_by', 'category',
                    'status','location','release_at',
                    'ticket_start_at', 'ticket_end_at',
                    'start_at','end_at',
                    'ticket_start_stamp', 'ticket_end_stamp',
                    'time_start_stamp','time_end_stamp',
                    'introduction', 'max_people','pic_url',
                    'updated_at','created_at',
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
            'pic_url'=>'预览图(不选则为默认图)'
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
            if(!$this->validate())throw new \Exception('创建信息需要调整!');

            if($this->pic_url)
            {
                if(!$this->setImg())
                    throw new \Exception('图片上传失败,请稍后重试');
            }
            else
                $this->pic_url=null;

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
            $model->summary=$this->getSummary();
            $model->max_people=$this->max_people;
            $model->current_serial=1;
            $model->activity_name=$this->activity_name;
            $model->category=$this->category;
            $model->pic_url=$this->pic_url;
            
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
     * Update:time_start_stamp,time_end_stamp
     * ticket_start_stamp,ticket_end_stamp
     * release_by,location,introduction,
     * max_people,activity_name,category,status,
     * ChangeStatus:status
     * ChangePicture:pic_url:允许为空,但为空表明使用默认预览图
     * ChangeSerial:current_serial,current_people
     * */
    public function infoUpdate($model,$scenario)
    {
        $transaction=Yii::$app->db->beginTransaction();
        try
        {
            switch($scenario)//过滤无效场景
            {
                case 'Update':
                case 'ChangePicture':
                case 'ChangeStatus':
                case 'ChangeSerial':
                    $this->scenario=$scenario;break;
                default:
                    throw new \Exception('场景参数错误');
                    break;
            }

            if(!$this->validate())throw new \Exception('更新信息需要调整');

            switch($scenario)
            {
                case 'Update':
                    {
                        $model->activity_name = $this->activity_name;
                        $model->status=$this->status;
                        if($this->status==Activity::STATUS_APPROVED)
                            $model->release_at=time()+7*3600;
                        $model->location=$this->location;
                        $model->release_by=$this->release_by;
                        $model->max_people=$this->max_people;
                        $model->introduction=$this->introduction;
                        $model->summary=$this->getSummary();
                        $model->start_at=strtotime($this->time_start_stamp);
                        $model->end_at=strtotime($this->time_end_stamp);
                        $model->updated_at=time()+7*3600;
                        $model->ticketing_start_at=strtotime($this->ticket_start_stamp);
                        $model->ticketing_end_at=strtotime($this->ticket_end_stamp);
                        $model->category=$this->category;
                        break;
                    }
                case 'ChangePicture':
                    {
                        //如果没改头像就不做动作
                        if($this->pic_url!=$model->pic_url)
                        {
                            if($this->pic_url)
                            {
                                if(!$this->setImg()) throw new \Exception('图片上传失败,请稍后重试');
                                //删除原有的图像文件
                                $oldFile=BASE_PATH.$model->pic_url;
                                if($model->pic_url&&file_exists($oldFile))unlink($oldFile);
                                $model->pic_url=$this->pic_url;
                            }
                            else
                            {
                                //删除原有的图像文件
                                $oldFile=BASE_PATH.$model->pic_url;
                                if($model->pic_url&&file_exists($oldFile))unlink($oldFile);
                                $model->pic_url=null;
                            }
                        }
                        break;
                    }
                case 'ChangeStatus':
                    {

                        //如果修改的状态不是通过该活动,则将所有与该活动相关联的票务状态改为取消
                        if($model->status==Activity::STATUS_APPROVED&&
                            $this->status!=Activity::STATUS_APPROVED)
                        {
                            $query=Ticket::find()
                                ->select('id')
                                ->where(['and',['activity_id'=>$model->id],['status'=>Ticket::STATUS_VALID]])
                                ->asArray()
                                ->all();

                            foreach ($query as $each)
                            {
                                if(!TicketForm::invalidateTicket($each['id']))
                                    throw new \Exception('票务取消失败');
                            }
                            $model->current_people=0;
                            $model->current_serial=1;
                        }
                        $model->status=$this->status;
                        break;
                    }
                case 'ChangeSerial':
                    {
                        if($this->current_people>$model->max_people)
                            throw new \Exception('活动人数已满');
                        $model->current_serial=$this->current_serial;
                        $model->current_people=$this->current_people;
                        break;
                    }
                default:
                    throw new \Exception('场景参数错误');
                    break;
            }

            if(!$model->save())throw new \Exception('活动信息修改失败!');

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


    //为正在填写的表单清除图片地址
    public function clearPic()
    {
        $this->pic_url=null;
    }


    //查询结果并返回成列表
    public static function getList($cond,$curPage = 1,$pageSize = 5,$sortOrder=['id'=>SORT_DESC])
    {
        $model=new ActivityForm();
        $select= ['id','activity_name','release_by','release_at',
                        'summary','max_people','current_people',
                        'location','status','start_at','end_at',
                        'ticketing_start_at','ticketing_end_at',
                        'pic_url'];
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

    //将表单的img_url正确处理,返回是否处理成功
    /*
     * 主要是将上传的文件复制到活动的文件夹下,
     * 因为这个图片上传组件只要一点击图片就会将图片上传到服务器,
     * 在表单中重复选择图片会导致多张图片上传至服务器,这样会有很多的无效图片
     * 我想的解决方案是,将真正用得到的图片放到另一个目录下
     * 服务器定期清理组件所指定的upload_files/temp里的文件夹,这样就可以省去很多空间,
     * */
    private function setImg()
    {
        if($this->pic_url)
        {
            //这里的文件处理搞得我脑阔有点疼
            $date=date('Y-m-d',(time()+7*3600));
            $newDir=BASE_PATH.'/upload_files/activity/'.$date;
            $oldDir=BASE_PATH.$this->pic_url;
            $fileName=substr($this->pic_url,25);
            if(!file_exists($newDir)) mkdir($newDir,0777,true);
            if(file_exists($newDir)&&copy($oldDir,$newDir.'/'.$fileName))
            {
                if($this->pic_url&&file_exists($oldDir))unlink($oldDir);
                $this->pic_url='/upload_files/activity/'.$date.$fileName;
                return true;
            }
        }
        return false;
    }

    //获取摘要
    private function getSummary($s=0,$e=90,$char='utf-8')
    {
        if(empty($this->introduction))
            return null;
        return(mb_substr(str_replace('&nbsp;',' ',
            strip_tags($this->introduction)),$s,$e,$char));
    }
}