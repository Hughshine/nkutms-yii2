<?php

namespace common\models;

use Yii;

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
 * @property TkActivity $activity
 * @property TkUser $user
 * @property TkTicketEvent[] $tkTicketEvents
 */
class Ticket extends \yii\db\ActiveRecord
{
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
            [['user_id', 'activity_id', 'serial_number', 'status'], 'integer'],
            [['created_at'], 'safe'],
            [['activity_id'], 'exist', 'skipOnError' => true, 'targetClass' => TkActivity::className(), 'targetAttribute' => ['activity_id' => 'id']],
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
            'user_id' => 'User ID',
            'activity_id' => 'Activity ID',
            'created_at' => 'Created At',
            'serial_number' => 'Serial Number',
            'status' => ' 0-有效，1-已退回withdraw，2-过期, 3 - 未知',
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
    public function getUser()
    {
        return $this->hasOne(TkUser::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTkTicketEvents()
    {
        return $this->hasMany(TkTicketEvent::className(), ['ticket_id' => 'id']);
    }
}
