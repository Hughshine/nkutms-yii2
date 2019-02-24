<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tk_ticket_event".
 *
 * @property int $id
 * @property int $ticket_id
 * @property int $user_id
 * @property int $activity_id
 * @property string $status 0-发布1-取消
 * @property string $update_at
 * @property int $operated_by_admin -1时，非管理员操作
 *
 * @property TkActivity $activity
 * @property TkAdmin $operatedByAdmin
 * @property TkTicket $ticket
 * @property TkUser $user
 */
class TicketEvent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $user_name;
    public $activity_name;
    public static function tableName()
    {
        return 'tk_ticket_event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ticket_id', 'user_id', 'activity_id', 'operated_by_admin'], 'integer'],
            [['status'], 'required'],
            [['update_at'], 'safe'],
            [['status'], 'string', 'max' => 1],
            [['activity_id'], 'exist', 'skipOnError' => true, 'targetClass' => 'admin\models\TkActivity', 'targetAttribute' => ['activity_id' => 'id']],
            [['operated_by_admin'], 'exist', 'skipOnError' => true, 'targetClass' => 'admin\models\Admin', 'targetAttribute' => ['operated_by_admin' => 'id']],
            [['ticket_id'], 'exist', 'skipOnError' => true, 'targetClass' => 'common\models\Ticket', 'targetAttribute' => ['ticket_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => 'common\models\User', 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ticket_id' => '票 ID',
            'user_id' => '票持有者 ID',
            'activity_id' => '活动 ID',
            'status' => '状态',//'0-发布1-取消'
            'update_at' => '发生时间',
            'operated_by_admin' => '操作管理员 ID',
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
    public function getOperatedByAdmin()
    {
        return $this->hasOne(admin\models\Admin::className(), ['id' => 'operated_by_admin']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTicket()
    {
        return $this->hasOne(Ticket::className(), ['id' => 'ticket_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function generateAndWriteNewTicketEvent($id,$user_id,$activity_id,$status,$operated_by_admin)
    {
        $ticket_event  = new TicketEvent();
        $ticket_event->ticket_id = $id;
        $ticket_event->user_id = $user_id;
        $ticket_event->activity_id = $activity_id;
        $ticket_event->status = $status;
        $ticket_event->update_at = time()+7*3600;
        $ticket_event->operated_by_admin = -1;
        $ticket_event->save(false);
    }
}
