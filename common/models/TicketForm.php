<?php
/**
 * Created by PhpStorm.
 * User: 31832
 * Date: 2019/3/16
 * Time: 20:19
 */
namespace common\models;

use common\exceptions\FieldException;
use common\exceptions\ModelNotFoundException;
use common\exceptions\ValidateException;
use Yii;

/*
 * 票务表单
 * */
class TicketForm extends BaseForm
{
    public $tk_id;
    public $user_id;
    public $activity_id;
    public $status;
    public $serial_number;

    public $is_api = false;

    public function rules()
    {
        return [
            [
                [
                    'user_id',
                    'activity_id',
                    'serial_number',
                    'status',
                    'created_at',
                    'updated_at',
                ],
                'integer',
                'on'=>['Create','Update','default',],
            ],
            [
                [
                    'user_id',
                    'activity_id',
                    'serial_number',
                    'status',
                ],
                'integer',
                'on'=>['Create','Update','default',],
            ],

            [
                'status', 'in', 'range' =>
                [
                    Ticket::STATUS_VALID,
                    Ticket::STATUS_WITHDRAW,
                    Ticket::STATUS_INVALID,
                    Ticket::STATUS_UNKNOWN
                ],
                'on'=>['Create','Update','ChangeStatus','default',],
            ],
            [
                ['activity_id'],
                'exist',
                'skipOnError' => true, 'targetClass' => Activity::className(),
                'targetAttribute' => ['activity_id' => 'id'],
                'on'=>['Create','Update','default',],
            ],
            [
                ['user_id'],
                'exist', 'skipOnError' => true, 'targetClass' => User::className(),
                'targetAttribute' => ['user_id' => 'id'],
                'on'=>['Create','Update','default',],
            ],
            ['activity_id','validateTicket','on'=>['Create','Update','default',],],
            ['serial_number','validateSerial','on'=>['Create','Update','default',],],
        ];
    }

    //设置场景值
    public function scenarios()
    {
        return
            [
                'Create' =>//表示某个场景所用到的信息,没标记出来的不会有影响
                    [
                        'user_id',
                        'activity_id',
                        'created_at',
                        'status',
                        'serial_number',
                        'updated_at',
                        'created_at',
                    ],
                'Update'=>
                    [
                        'status',
                        'user_id',
                        'activity_id',
                        'serial_number',
                        'updated_at',
                    ],
                'ChangeStatus'=>
                    [
                        'status',
                        'updated_at',
                    ],
                'default'=>
                    [
                        'tk_id',
                        'user_id',
                        'activity_id',
                        'created_at',
                        'status',
                        'serial_number',
                        'updated_at',
                        'created_at',
                    ],
            ];
    }

    /**
     * 验证用户是否已经参与了该活动
     * @param $attribute
     */
    public function validateTicket($attribute)
    {
        if (!$this->hasErrors())
        {
            if($this->scenario=='Update'&&$this->status!=Ticket::STATUS_VALID)return;

            if(Ticket::findOne(
                [
                    'user_id'=>$this->user_id,
                    'status'=>Ticket::STATUS_VALID,
                    'activity_id'=>$this->activity_id
                ]))
                $this->addError($attribute, '已经参与了这个活动');
        }
    }

    /**
     * 检查票务对应的活动里是否有相同序列号的票,如果有,就出错
     * @param $attribute
     */
    public function validateSerial($attribute)
    {
        if (!$this->hasErrors())
        {
            if(Ticket::findOne(
                [
                    'serial_number'=>$this->serial_number,
                    'status'=>Ticket::STATUS_VALID,
                    'activity_id'=>$this->activity_id
                ]))
                $this->addError($attribute, '序列号不正确');
        }
    }

    public static function tableName()
    {
        return 'tk_ticket';
    }

    public function attributeLabels()
    {
        return
            [
                'tk_id'=>'ID',
                'user_id'=>'持有者ID',
                'activity_id'=>'活动ID',
                'status'=>'状态',
                'created_at'=>'票务创建时间',
                'updated_at'=>'票务更新时间',
                'serial_number'=>'序列号',
            ];
    }

    /**
     * 根据这个表单的信息创建一个票务记录,返回新创建的模型
     * 必须的字段为:user_id,activity_id,status,serial_number
     * @return Ticket
     * @throws ValidateException
     * @throws \Exception
     */
    public function create()
    {
        $this->scenario='Create';

        if(!$this->validate())
            $this->throwValidateException('TicketForm::create:票务信息有误');

        $model = new Ticket();
        $model->user_id=$this->user_id;
        $model->activity_id=$this->activity_id;
        $model->status=$this->status;
        $model->serial_number=$this->serial_number;

        $transaction=Yii::$app->db->beginTransaction();
        try
        {
            if(!$model->save())
                $this->throwValidateException('TicketForm::create:票务创建失败!');

            $this->tk_id=$model->id;//用于创建后导向相关页面,id会在model save后自动获得

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
     * 根据表单的信息更新$model
     * 必须的字段为:
     * user_id, activity_id,status,serial_number
     * @param $model Ticket
     * @param $scenario string
     * @return bool
     * @throws ValidateException
     * @throws \Exception
     */
    public function infoUpdate($model,$scenario)
    {
        $this->updateAction_FilterScenario($scenario);

        if(!$this->validate())
            $this->throwValidateException('TicketForm::infoUpdate:修改信息需要调整');

        switch($scenario)
        {
            case 'Update':$model=$this->updateActionInUpdate($model);break;
            case 'ChangeStatus':
                $this->scenario=$scenario;
                break;
            default:break;
        }

        $transaction=Yii::$app->db->beginTransaction();
        try
        {
            if($scenario=='ChangeStatus'&&$model->status==Ticket::STATUS_VALID&&$this->status!=$model->status)
            {
                $act_form=new ActivityForm();
                $act=Activity::findOne(['id'=>$model->activity_id]);
                if($act==null)
                    throw new FieldException('TicketForm::infoUpdate:票对应的活动不存在');
                $act_form->scenario='ChangeSerial';
                $act_form->current_serial=$act->current_serial;
                $act_form->current_people=$act->current_people-1;
                if($act_form->current_people<0)
                    $act_form->current_people=0;
                $act_form->infoUpdate($act,'ChangeSerial');
                $model->status=$this->status;
            }
            if(!$model->save())
                $this->throwValidateException('TicketForm::infoUpdate:模型保存失败');
            $transaction->commit();
            return true;
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



    //取消id为tk_id的票务,返回是否操作成功
    //如果tk_id不存在,返回也为false
    /**
     * @param $tk_id
     * @return bool
     * @throws FieldException
     * @throws ModelNotFoundException
     * @throws \Exception
     */
    public static function invalidateTicket($tk_id)
    {
        if(!is_numeric($tk_id))
            throw new FieldException('TicketForm::invalidateTicket:票ID必须为整数');

        $model=Ticket::findOne(['id'=>$tk_id]);

        if(!$model)throw new ModelNotFoundException(sprintf('TicketForm::invalidateTicket:找不到ID为%d的票',$tk_id));

        $form=new TicketForm();
        $form->status=Ticket::STATUS_INVALID;
        return $form->infoUpdate($model,'ChangeStatus');
    }


    /**
     * infoUpdate函数里用到的过滤场景参数的方法
     * @param string $scenario
     * @throws FieldException
     */
    private function updateAction_FilterScenario($scenario)
    {
        if(!is_string($scenario))
            throw new FieldException('TicketForm::updateAction_FilterScenario:场景参数必须为字符串');
        switch($scenario)//过滤无效场景参数
        {
            case 'Update':
            case 'ChangeStatus':
                $this->scenario=$scenario;
                break;
            default:
                throw new FieldException('TicketForm::infoUpdate:场景参数不存在');
        }
    }

    /**
     * @param Ticket $model
     * @return Ticket
     */
    private function updateActionInUpdate($model)
    {
        $model->user_id=$this->user_id;
        $model->activity_id=$this->activity_id;
        $model->status=$this->status;
        $model->serial_number=$this->serial_number;
        return $model;
    }

}
