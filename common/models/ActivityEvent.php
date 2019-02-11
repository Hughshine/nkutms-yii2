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
            [['operated_by_admin'], 'exist', 'skipOnError' => true, 'targetClass' => Admin::className(), 'targetAttribute' => ['operated_by_admin' => 'id']],
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
            'organizer_id' => 'Organizer ID',
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
}
