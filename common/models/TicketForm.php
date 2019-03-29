<?php
/**
 * Created by PhpStorm.
 * User: 31832
 * Date: 2019/3/16
 * Time: 20:19
 */
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/*
 * 票务表单
 * */
class TicketForm extends ActiveRecord
{
    public $tk_id;
    public $user_id;
    public $activity_id;
    public $status;
    public $serial_number;

    public $lastError;//用于存放最后一次异常信息

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

    public function validateTicket($attribute, $params)
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
                $this->addError($attribute, '你已经参与了这个活动');
        }
    }

    public function validateSerial($attribute, $params)
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

     //根据这个表单的信息创建一个票务记录,返回新创建的模型或者null(创建失败)
    /*
     * 必须的字段为:user_id,activity_id,status,serial_number
     * */
    public function create()
    {
        $this->scenario='Create';
        $transaction=Yii::$app->db->beginTransaction();
        try
        {
            if(!$this->validate()) throw new \Exception('票务信息有冲突');
            $model = new Ticket();
            $model->user_id=$this->user_id;
            $model->activity_id=$this->activity_id;
            $model->status=$this->status;
            $model->serial_number=$this->serial_number;

            if(!$model->save()) throw new \Exception('票务创建失败!');

            $this->tk_id=$model->id;//用于创建后导向相关页面

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


    //根据表单的信息更新$model
    /*
     * 必须的字段为:
     * user_id, activity_id,status,serial_number
     * */
    public function infoUpdate($model,$scenario)
    {
        switch($scenario)//过滤无效场景参数
        {
            case 'Update':
            case 'ChangeStatus':
                $this->scenario=$scenario;
                break;
            default:
                Yii::$app->getSession()->setFlash('warning', '场景参数错误');
                return false;
        }
        $transaction=Yii::$app->db->beginTransaction();
        try
        {
            if(!$this->validate()) {
                var_dump($this->errors);
                throw new \Exception('修改信息需要调整');
            }

            switch($scenario)//过滤无效场景参数
            {
                case 'Update':
                    {
                        $model->user_id=$this->user_id;
                        $model->activity_id=$this->activity_id;
                        $model->status=$this->status;
                        $model->serial_number=$this->serial_number;
                        break;
                    }
                case 'ChangeStatus':
                    {
                        $model->status=$this->status;
                        break;
                    }
                default:break;
            }

            if(!$model->save())throw new \Exception('票务修改失败!');
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

    //取消id为tk_id的票务,返回是否操作成功
    //如果tk_id不存在,返回也为false
    public static function invalidateTicket($tk_id)
    {
        $model=Ticket::findOne(['id'=>$tk_id]);
        if(!$model)return false;
        $form=new TicketForm();
        $form->status=Ticket::STATUS_INVALID;
        return $form->infoUpdate($model,'ChangeStatus');
    }


}
