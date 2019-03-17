<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tk_ticket".
 *
 * @property int $id
 * @property int $user_id
 * @property int $activity_id
 * @property string $created_at
 * @property int $serial_number
 * @property int $status  0-有效，1-已退回withdraw，2-过期, 3 - 未知
 *
 * @property Activity $activity
 * @property User $user
 * @property TicketEvent[] $tkTicketEvents
 */
class Ticket extends ActiveRecord
{
    const STATUS_VALID=0;
    const STATUS_WITHDRAW=1;
    const STATUS_INVALID=2;
    const STATUS_UNKNOWN=3;
    public $lastError;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tk_ticket';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'activity_id', 'serial_number', 'status','created_at'], 'integer'],

            ['status', 'in', 'range' => [self::STATUS_VALID, self::STATUS_WITHDRAW,
                                                    self::STATUS_INVALID,self::STATUS_UNKNOWN]],

            [['activity_id'], 'exist', 'skipOnError' => true, 'targetClass' => Activity::className(), 'targetAttribute' => ['activity_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),//自动填充时间字段功能
                'attributes' => [
                    //当插入时填充created_at和updated_at
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    //当更新时填充updated_at
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }



    //曾经的尝试,发现parent::save总是返回false,遂放弃
    //后来的查找结果:准备尝试
    /*要在insert或update前做事情应该监听事件
    也是因为很多新手不了解事件这个知识点所以没有想到用事件监听

    要在模型insert或update前做自己的事其实是这样用事件的：

    $user = new User([
        'name' => 'Jay'
        'age' => 17
    ]);
    $user->on(User::EVENT_BEFORE_INSERT, function(){
        echo '准备insert了';
    });
    $user->save(); // 准备insert了


    $user2 = User::findOne(111);
    $user2->on(User::EVENT_BEFORE_UPDATE, function(){
        echo '准备update了';
    });
    $user2->save(); // 准备update了
    */


    //重写save方法,保证保存时满足活动当前人数小于等于最大人数
    /*public function save($runValidation = true, $attributeNames = NULL)
    {
        $activity=$this->getActivity();
        if(!$activity)return false;

        $transaction=Yii::$app->db->beginTransaction();
        try
        {
            //在这我学会了很多.....
            /*这里是实验
            $query=(new \yii\db\Query())//新建查询
            ->select(['tk.id','activity_id','user_id',
                'activity_name','max_people'])//选取字段
                //选取字段也可以写成'tk.id,activity_id,user_id'的非数组写法
            ->from('tk_ticket tk')//表名后加空格表示表的别名
            ->where('tk.id=:id',[':id'=>$this->id])//用此方法来传参比较,比较安全,不怕SQL注入攻击
            ->join('LEFT JOIN','tk_activity act','act.id=tk.activity_id')
            //->indexBy('id')
            //如果用all()方法返回所有结果,则可以按indexBy按照返回数组的id来作为返回结果的第一个下标,之所以不用前缀是因为这时候已经返回结果了,只有一个id,这样取数据的操作变为$query['id']['字段名']
            ->one();//返回单个结果
            //这时可以通过$query['字段名']来获取数据
            */
            /*$query=(new \yii\db\Query())//查找数据库里是否有这张票
            ->select('count(*),serial_number')
            ->from('tk_ticket tk')
            ->where('user_id=:user_id AND activity_id=:act_id',[':user_id'=>$this->user_id,':act_id'=>$this->activity_id])
            ->one();
            if($query['count(*)']>=1)//如果有这张票,那么说明已经存在这张票,不能保存
                throw new \Exception('该用户已经有这张票的记录');
            //到这说明这张票之前不存在        
            if(parent::save(false,$attributeNames))
                throw new \Exception($query['count(*)']'票务修改失败');
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
    }*/
    

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '持有者ID',
            'activity_id' => '活动ID',
            'created_at' => '记录创建时间',
            'updated_at' => '记录更新时间',
            'serial_number' => '序列号',
            'status' => '状态',//' 0-有效，1-已退回withdraw，2-过期, 3 - 未知',
        ];
    }

    public function fields()
        {
            return [
                "ticket_id" => "id",
                "user_id",
                "activity_id",
                "activity_name" => function($model)
                {
                    return $this->activity->activity_name;
                },
                "fetch_time" => "created_at", //抢票时间
                "serial_number",
                "status" => function($model)
                {
                    switch ($model->status) {
                        case 0:
                            return '有效';
                            break;
                        case 1:
                            return '退回';
                            break;
                        case 2:
                            return '过期';
                            break;
                        default:
                            return '未知';
                            break;
                    }
                },
            ];
        }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivity()
    {
        return $this->hasOne(Activity::className(), ['id' => 'activity_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }


    public function generateAndWriteNewTicket($user_id,$activity_id,$current_serial,$status)
    {
        $ticket = new Ticket();
        $ticket->user_id = $user_id;
        $ticket->activity_id = $activity_id;
        $ticket->created_at = time()+7*3600;
        $ticket->serial_number = $current_serial;
        $ticket->status = 0;
        $ticket->save(false);
        return $ticket;
    }
}
