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
            [['activity_id'], 'exist', 'skipOnError' => true, 'targetClass' => TkActivity::className(), 'targetAttribute' => ['activity_id' => 'id']],
            [['operated_by_admin'], 'exist', 'skipOnError' => true, 'targetClass' => TkAdmin::className(), 'targetAttribute' => ['operated_by_admin' => 'id']],
            [['ticket_id'], 'exist', 'skipOnError' => true, 'targetClass' => TkTicket::className(), 'targetAttribute' => ['ticket_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => TkUser::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ticket_id' => 'Ticket ID',
            'user_id' => 'User ID',
            'activity_id' => 'Activity ID',
            'status' => '0-发布1-取消',
            'update_at' => 'Update At',
            'operated_by_admin' => '-1时，非管理员操作',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivity()
    {
        return $this->hasOne(TkActivity::className(), ['id' => 'activity_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperatedByAdmin()
    {
        return $this->hasOne(TkAdmin::className(), ['id' => 'operated_by_admin']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTicket()
    {
        return $this->hasOne(TkTicket::className(), ['id' => 'ticket_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(TkUser::className(), ['id' => 'user_id']);
    }
}
