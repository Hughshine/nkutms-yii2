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
            [['user_id', 'activity_id', 'serial_number', 'status','created_at'], 'integer'],

            [
                'status', 'in', 'range' =>
                [
                    Ticket::STATUS_VALID,
                    Ticket::STATUS_WITHDRAW,
                    Ticket::STATUS_INVALID,
                    Ticket::STATUS_UNKNOWN
                ]
            ],
            [['activity_id'], 'exist', 'skipOnError' => true, 'targetClass' => Activity::className(), 'targetAttribute' => ['activity_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public static function tableName()
    {
        return 'tk_ticket';
    }

    //设置场景值
    public function scenarios()
    {
        return
            [
            'Create' =>//表示某个场景所用到的信息,没标记出来的不会有影响
                [
                    'tk_id',
                    'user_id',
                    'activity_id',
                    'created_at',
                    'status',
                    'serial_number',
                    'updated_at',
                ],
            'Update'=>
                [
                    'tk_id',
                    'user_id',
                    'activity_id',
                    'status',
                    'serial_number',
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
                 ],
        ];
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
            if(!$this->validate())throw new \Exception('创建信息需要调整');
            $model = new Ticket();
            $model->user_id=$this->user_id;
            $model->activity_id=$this->activity_id;
            $model->status=$this->status;
            $model->serial_number=$this->serial_number;

            if(!$model->save())throw new \Exception('票务创建失败!');
            //此处可以写一个afterCreate方法来处理创建后事务

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
    public function infoUpdate($model)
    {
        $this->scenario='Update';
        $transaction=Yii::$app->db->beginTransaction();
        try
        {
            if(!$this->validate())throw new \Exception('修改信息需要调整');
            $model->user_id=$this->user_id;
            $model->activity_id=$this->activity_id;
            $model->status=$this->status;
            $model->serial_number=$this->serial_number;

            if(!$model->save())throw new \Exception('资料修改失败!');
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
}
