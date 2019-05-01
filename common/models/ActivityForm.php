<?php
/**
 * Created by PhpStorm.
 * User: 31832
 * Date: 2019/3/9
 * Time: 10:32
 */
namespace  common\models;

use common\exceptions\ProjectException;
use common\exceptions\FieldException;
use common\exceptions\ProcessException;
use common\exceptions\ValidateException;
use common\exceptions\ModelNotFoundException;
use Yii;

/**
 * 活动表单模型
 * Class ActivityForm
 * @package common\models
 * @property integer $updated_at
 * @property integer $release_at
 */
class ActivityForm extends BaseForm
{
    //表单字段
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
    //注意:以下关于时间的字段全是字符串形式,在保存模型前要转化成整数形式
    //从整数转化成字符串形式的格式是date('Y-m-d H:i' , $model->start_at)
    public $start_at_string;
    public $end_at_string;
    public $ticketing_start_at_string;
    public $ticketing_end_at_string;

    public $org_name;//用于查找发布者名字


    //验证规则
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
                        'max_people',
                        'status',
                        'start_at_string',
                        'end_at_string',
                        'ticketing_start_at_string',
                        'ticketing_end_at_string',
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


                [
                    'start_at_string', 'compare',
                    'compareValue'=>date('Y-m-d H:i:s' , time()+7*3600),
                    'operator' => '>','message'=>'不能早于当前的时间',
                    'on'=>['Create','default',]
                ],
                [
                    'ticketing_start_at_string', 'compare',
                    'compareValue'=>date('Y-m-d H:i:s' , time()+7*3600),
                    'operator' => '>','message'=>'不能早于当前的时间',
                    'on'=>['Create','default',]
                ],
                [
                    'ticketing_end_at_string', 'compare',
                    'compareAttribute'=>'ticketing_start_at_string',
                    'operator' => '>','message'=>'结束时间不能早于开始时间',
                    'on'=>['Create','Update','default',],
                ],
                [
                    'end_at_string',
                    'compare','compareAttribute'=>
                    'start_at_string', 'operator' => '>',
                    'message'=>'结束时间不能早于开始时间',
                    'on'=>['Create','Update','default',],
                ],
                [
                    'start_at_string', 'compare',
                    'compareAttribute'=>'ticketing_end_at_string', 'operator' => '>',
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
        return
            [
            'Create' =>//用string形的时间格式来填写表格,主要是因为组件返回的是字符串类型
                [
                    'activity_name', 'release_by', 'category',
                    'status','location','release_at',
                    'start_at_string','end_at_string',
                    'ticketing_start_at_string', 'ticketing_end_at_string',
                    'introduction', 'max_people','pic_url',
                    'updated_at','created_at',
                ],
            'Update' =>
                [
                    'activity_name', 'release_by', 'category',
                    'status','location','release_at',
                    'start_at_string',
                    'end_at_string',
                    'ticketing_start_at_string',
                    'ticketing_end_at_string',
                    'introduction', 'max_people','updated_at',
                ],
            'ChangeStatus'=>['status','release_at','updated_at'],
            'ChangePicture'=>['pic_url','status','updated_at'],
            'ChangeSerial'=>['current_serial','current_people','updated_at'],
            'default'=>
                [
                    'activity_name', 'release_by', 'category',
                    'status','location','release_at',
                    'start_at_string','end_at_string',
                    'ticketing_start_at_string', 'ticketing_end_at_string',
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
            'start_at_string' => '活动开始时间',
            'end_at_string' => '活动结束时间',
            'release_at' => '发布时间',
            'updated_at' => '上一次编辑时间',
            'ticketing_start_at_string' => '票务发布时间',
            'ticketing_end_at_string' => '票务结束时间',
            'current_serial'=>'票务序列号',
            'location'=>'活动地点',
            'release_by'=>'发布者ID',
            'pic_url'=>'预览图(不选则为默认图)'
        ];
    }

    /**以当前表单的信息创建一个活动,返回这个活动的模型
     * 场景:Create 必须字段如下:
     * start_at_string,end_at_string
     * ticketing_start_at_string,ticketing_end_at_string
     * release_by,location,introduction,
     * max_people,activity_name,category
     * 每个字段对应的约束见rules
     *
     * @return Activity
     * @throws ValidateException
     * @throws \Exception
     */
    public function create()
    {
        $this->scenario='Create';

        $this->updated_at=$this->release_at=time()+7*3600;

        if (!$this->validate())
            $this->throwValidateException('ActivityForm::create:创建表单不能通过验证');

        $this->createAction_HandlePicUrl();

        $model = $this->createAction_NewAModelFromForm();

        $transaction=Yii::$app->db->beginTransaction();
        try
        {
            if (!$model->save())
                $this->throwValidateException('ActivityForm::create:活动模型保存失败');

            //此处可以写一个afterCreate方法来处理创建后事务
            $transaction->commit();
            return $model;
        }
        catch(ValidateException $exception)
        {
            $transaction->rollBack();
            throw $exception;
        }
        catch(\Exception $exception)
        {
            $transaction->rollBack();
            throw $exception;
        }
    }

    /**
     * 以当前表单的信息更新$activity活动信息,返回是否成功
     * 必须字段为:
     * Update:
     * start_at_string,end_at_string
     * ticketing_start_at_string,ticketing_end_at_string
     * release_by,location,introduction,
     * max_people,activity_name,category,status,
     *
     * ChangeStatus:status
     *
     * ChangePicture:pic_url:允许为空,但为空表明使用默认预览图
     *
     * ChangeSerial:current_serial,current_people
     *
     * @param Activity $model
     * @param string $scenario
     * @return bool
     * @throws FieldException
     * @throws ProcessException
     * @throws ValidateException
     * @throws \Exception
     */
    public function infoUpdate($model,$scenario)
    {
        $this->updateAction_FilterScenario($scenario);

        if(!$this->validate())
            $this->throwValidateException('ActivityForm::infoUpdate:表单信息不能通过验证');

        switch($scenario)
        {
            case 'Update':$model=$this->updateActionInUpdate($model);break;
            case 'ChangePicture':$model=$this->updateActionInChangePicture($model);break;
            case 'ChangeStatus':$model->status=$this->status;break;
            case 'ChangeSerial':$model=$this->updateActionInChangeSerial($model);break;
            default:throw new FieldException('ActivityForm::infoUpdate:场景参数不存在');break;
        }

        $transaction=Yii::$app->db->beginTransaction();
        try
        {
            if($this->scenario=='ChangeStatus')
                $this->updateActionInvalidateTickets($model);

            if(!$model->save())
                $this->throwValidateException('ActivityForm::infoUpdate:活动信息保存失败,具体信息见errors');

            $transaction->commit();
            return true;
        }
        catch(\Exception $exception)
        {
            $transaction->rollBack();
            throw $exception;
        }
    }

    /**
     * 输入用户ID,活动ID,检查活动人数和活动状态,合法创建一个票记录,若不符合条件,
     * 会抛出相应异常
     * @param $userId integer
     * @param $actId integer
     * @return Ticket
     * @throws FieldException
     * @throws ModelNotFoundException
     * @throws ProcessException
     * @throws \Exception
     */
    public static function createTicket($userId,$actId)
    {
        if(!is_numeric($userId)|| !is_numeric($actId))
            throw new FieldException('ActivityForm::createTicket:用户ID和活动ID必须为整数');

        $user=User::findOne(['id'=>$userId,'status'=>User::STATUS_ACTIVE]);
        if(!$user)
            throw new ModelNotFoundException(sprintf('ActivityForm::createTicket:找不到ID为%d的用户',$userId));

        $act=Activity::findOne(['id'=>$actId,'status'=>Activity::STATUS_APPROVED]);
        if(!$act)
            throw new ModelNotFoundException(sprintf('ActivityForm::createTicket:找不到ID为%d的活动',$actId));

        if(time()+7*3600<$act->ticketing_start_at)
            throw new FieldException('ActivityForm::createTicket:活动票务还没开始');
        if(time()+7*3600>$act->ticketing_end_at)
            throw new FieldException('ActivityForm::createTicket:活动票务已经结束');

        $ticketForm=new TicketForm();
        $actForm=new ActivityForm();
        $ticketForm->activity_id=$actId;
        $ticketForm->user_id=$userId;
        $ticketForm->status=Ticket::STATUS_VALID;
        $ticketForm->serial_number=$act->current_serial;
        $actForm->current_serial=$act->current_serial+1;
        $actForm->current_people=$act->current_people+1;

        $transaction=Yii::$app->db->beginTransaction();
        try
        {
            $model=$ticketForm->create();
            $actForm->infoUpdate($act,'ChangeSerial');
            $transaction->commit();
            return $model;
        }
        catch(ProjectException $exception)
        {
            $transaction->rollBack();
            //形成异常链
            $newException=new ProcessException('ActivityForm::createTicket:创建票务失败');
            $newException->preException=$exception;
            throw $newException;
        }
        catch(\Exception $exception)
        {
            $transaction->rollBack();
            $newException=new ProcessException('ActivityForm::createTicket:未知异常:');
            $newException->preException=$exception;
            throw $newException;
        }
    }

    /**
     * 取消一个用户对应一个活动的票务,返回是否操作成功
     * @param integer $act_id
     * @param integer $user_id
     * @return bool
     * @throws FieldException
     * @throws ModelNotFoundException
     * @throws ProcessException
     * @throws \Exception
     */
    public static function cancelTicket($act_id,$user_id)
    {
        if(!is_numeric($act_id)||!is_numeric($user_id))
            throw new FieldException('ActivityForm::cancelTicket:ID必须为整数');

        $ticket=Ticket::findOne(['user_id'=>$user_id,'activity_id'=>$act_id,'status'=>Ticket::STATUS_VALID]);
        if(!$ticket)
            throw new ModelNotFoundException(sprintf('ActivityForm::cancelTicket:ID为%d的用户并没有参与ID为%d的活动',$user_id,$act_id));
        $act=Activity::findOne(['id'=>$act_id,'status'=>Activity::STATUS_APPROVED]);
        if(!$act)
            throw new ModelNotFoundException(sprintf('ActivityForm::cancelTicket:找不到ID为%d的活动',$act_id));

        $ticketForm=new TicketForm();
        $actForm=new ActivityForm();
        $ticketForm->status=Ticket::STATUS_WITHDRAW;
        $actForm->current_people=$act->current_people-1;
        if($actForm->current_people<0)$actForm->current_people=0;
        $actForm->current_serial=$act->current_serial;

        $transaction=Yii::$app->db->beginTransaction();
        try
        {
            $ticketForm->infoUpdate($ticket,'ChangeStatus');
            $actForm->infoUpdate($act,'ChangeSerial');
            $transaction->commit();
            return true;
        }
        catch(ProjectException $exception)
        {
            $transaction->rollBack();
            //形成异常链
            $newException=new ProcessException('ActivityForm::cancelTicket:取消票务失败');
            $newException->preException=$exception;
            throw $newException;
        }
        catch(\Exception $exception)
        {
            $transaction->rollBack();
            $newException=new ProcessException('ActivityForm::cancelTicket:未知异常:');
            $newException->preException=$exception;
            throw $newException;
        }
    }

    /**
     * 将整数时间转化为字符串时间保存在该表单内
     * @param integer $start_at
     * @param integer $end_at
     * @param integer $ticketing_start_at
     * @param integer $ticketing_end_at
     */
    public function getStringTimeFromIntTime($start_at,$end_at,$ticketing_start_at,$ticketing_end_at)
    {
        $this->start_at_string=date('Y-m-d H:i' , $start_at);
        $this->end_at_string=date('Y-m-d H:i' , $end_at);
        $this->ticketing_start_at_string=date('Y-m-d H:i' , $ticketing_start_at);
        $this->ticketing_end_at_string=date('Y-m-d H:i' , $ticketing_end_at);
    }

    /**
     * Update场景的写入模型动作
     * @param $model Activity
     * @return Activity
     */
    public function updateActionInUpdate($model)
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
        $model->start_at=strtotime($this->start_at_string);
        $model->end_at=strtotime($this->end_at_string);
        $model->ticketing_start_at=strtotime($this->ticketing_start_at_string);
        $model->ticketing_end_at=strtotime($this->ticketing_end_at_string);
        $model->updated_at=time()+7*3600;
        $model->category=$this->category;
        return $model;
    }

    /**
     * 将表单的img_url正确处理,返回是否处理成功
     * 主要是将上传的文件复制到活动的文件夹下,
     * 因为这个图片上传组件只要一点击图片就会将图片上传到服务器,
     * 在表单中重复选择图片会导致多张图片上传至服务器,这样会有很多的无效图片
     * 我想的解决方案是,将真正用得到的图片放到另一个目录下
     * 服务器定期清理组件所指定的upload_files/temp里的文件夹,这样就可以省去很多空间,
     * @return bool
     */
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

    /**
     * 从content里获取摘要存放到summary字段里
     * @param integer $s
     * @param integer $e
     * @param string $char
     * @return string|null
     */
    private function getSummary($s=0,$e=90,$char='utf-8')
    {
        if(empty($this->introduction))
            return null;
        return(mb_substr(str_replace('&nbsp;',' ',
            strip_tags($this->introduction)),$s,$e,$char));
    }

    /**
     * infoUpdate函数里用到的过滤场景参数的方法
     * @param string $scenario
     * @throws FieldException
     */
    private function updateAction_FilterScenario($scenario)
    {
        if(!is_string($scenario))
            throw new FieldException('ActivityForm::updateAction_FilterScenario:场景参数必须为字符串');
        switch($scenario)//过滤无效场景
        {
            case 'Update':
            case 'ChangePicture':
            case 'ChangeStatus':
            case 'ChangeSerial':
                $this->scenario=$scenario;break;
            default:
                throw new FieldException('ActivityForm::infoUpdate:场景参数不存在');
                break;
        }
    }

    /**
     * Update场景的写入模型动作
     * @param $model Activity
     * @throws ProcessException
     * @return Activity
     */
    private function updateActionInChangeSerial($model)
    {
        if($this->current_people>$model->max_people)
            throw new ProcessException('ActivityForm::infoUpdate:活动人数已满');
        $model->current_serial=$this->current_serial;
        $model->current_people=$this->current_people;
        return $model;
    }

    /**
     * ChangePicture场景的写入模型动作
     * @param $model Activity
     * @throws ProcessException
     * @return Activity
     */
    private function updateActionInChangePicture($model)
    {
        //如果没改头像就不做动作
        if($this->pic_url!=$model->pic_url)
        {
            if($this->pic_url)
            {
                if(!$this->setImg())
                    throw new ProcessException('ActivityForm::infoUpdate:图片上传失败,请稍后重试');

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
            $model->status=Activity::STATUS_UNAUDITED;
        }
        return $model;
    }

    /**
     * 取消活动时连带取消所有关联到该活动的票务动作
     * @param Activity $model
     * @throws FieldException
     * @throws ModelNotFoundException
     * @return Activity
     */
    private function updateActionInvalidateTickets($model)
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
                TicketForm::invalidateTicket($each['id']);
            $model->current_people=0;
            $model->current_serial=1;
        }
        return $model;
    }

    /**
     * 根据当前表单的信息新建一个活动模型
     * @return Activity
     */
    private function createAction_NewAModelFromForm()
    {
        $model=new Activity();
        $model->start_at = strtotime($this->start_at_string);
        $model->end_at = strtotime($this->end_at_string);
        $model->ticketing_start_at = strtotime($this->ticketing_start_at_string);
        $model->ticketing_end_at = strtotime($this->ticketing_end_at_string);
        $model->release_at = time() + 7 * 3600;
        $model->current_people = 0;
        $model->release_by = $this->release_by;
        $model->status = Activity::STATUS_UNAUDITED;
        $model->location = $this->location;
        $model->introduction = $this->introduction;
        $model->summary = $this->getSummary();
        $model->max_people = $this->max_people;
        $model->current_serial = 1;
        $model->activity_name = $this->activity_name;
        $model->category = $this->category;
        $model->pic_url = $this->pic_url;
        return $model;
    }

    /**
     * 创建时处理图片地址:
     * @throws ProcessException
     */
    private function createAction_HandlePicUrl()
    {
        if ($this->pic_url)
        {
            if (!$this->setImg())
                throw new ProcessException('ActivityForm::create:图片上传失败');
        }
        else
            $this->pic_url = null;
    }

}