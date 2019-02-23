<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tk_activity_event".
 *
 * @property int $id
 * @property int $organizer_id
 * @property int $activity_id
 * @property string $status 0-发布1-取消
 * @property string $update_at
 * @property int $operated_by_admin -1时，非管理员操作
 *
 * @property Activity $activity
 * @property Admin $operatedByAdmin
 * @property Organizer $organizer
 */
class ActivityEvent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $activity_name;
    public $org_name;
    public static function tableName()
    {
        return 'tk_activity_event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['organizer_id', 'activity_id', 'operated_by_admin'], 'integer'],
            [['status'], 'required'],
            [['update_at'], 'safe'],
            [['status'], 'string', 'max' => 1],
            [['activity_id'], 'exist', 'skipOnError' => true, 'targetClass' => Activity::className(), 'targetAttribute' => ['activity_id' => 'id']],
            [['operated_by_admin'], 'exist', 'skipOnError' => true, 'targetClass' => 'admin\models\Admin', 'targetAttribute' => ['operated_by_admin' => 'id']],
            [['organizer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organizer::className(), 'targetAttribute' => ['organizer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'organizer_id' => '发布者ID',
            'activity_id' => '活动ID',
            'status' => '状态',
            'update_at' => '发生时间',
            'operated_by_admin' => '上一次操作的管理员ID',
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
        return $this->hasOne(Admin::className(), ['id' => 'operated_by_admin']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganizer()
    {
        return $this->hasOne(Organizer::className(), ['id' => 'organizer_id']);
    }

    public function generateAndWriteNewActivityEvent($org_id, $activity_id, $status, $operated_by_admin)
    {
        $activity_event = new ActivityEvent();
        $activity_event->organizer_id = $org_id;
        $activity_event->activity_id = $activity_id;
        $activity_event->status = $status;
        $activity_event->update_at = time()+7*3600;
        $activity_event->operated_by_admin = $operated_by_admin;
        $activity_event->save(false);
    }
}
