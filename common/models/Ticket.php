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
 * @property Activity $activity
 * @property User $user
 * @property TicketEvent[] $tkTicketEvents
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
            [['activity_id'], 'exist', 'skipOnError' => true, 'targetClass' => Activity::className(), 'targetAttribute' => ['activity_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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

    public function fields()
        {
            return [
                "ticket_id" => "id",
                "user_id",
                "activity_id",
                "activity_name" => function($model)
                {
                    return $this->activity->name;
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTicketEvents()
    {
        return $this->hasMany(TicketEvent::className(), ['ticket_id' => 'id']);
    }
}
