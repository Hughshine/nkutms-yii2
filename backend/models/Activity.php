<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tk_activity".
 *
 * @property int $id
 * @property string $activity_name
 * @property int $release_by organizer/-id
 * @property int $category 标记活动类别 0-讲座 1-文艺 2-其他 3-未知
 * @property int $status 状态0-正常1-取消2-结束
 * @property string $location
 * @property int $release_at
 * @property int $ticketing_start_at
 * @property int $ticketing_end_at
 * @property int $start_at
 * @property int $end_at
 * @property int $updated_at
 * @property string $introduction 介绍
 * @property int $current_people
 * @property int $max_people
 * @property int $current_serial 用于产生票务的序列号
 * @property string $pic_url 暂不支持传入图片
 *
 * @property TkOrganizer $releaseBy
 * @property TkActivityEvent[] $tkActivityEvents
 * @property TkTicket[] $tkTickets
 * @property TkTicketEvent[] $tkTicketEvents
 */
class Activity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tk_activity';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['release_by', 'category', 'status', 'release_at', 'ticketing_start_at', 'ticketing_end_at', 'start_at', 'end_at', 'updated_at', 'current_people', 'max_people', 'current_serial'], 'integer'],
            [['activity_name'], 'string', 'max' => 32],
            [['location', 'introduction', 'pic_url'], 'string', 'max' => 255],
            [['release_by'], 'exist', 'skipOnError' => true, 'targetClass' => TkOrganizer::className(), 'targetAttribute' => ['release_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'activity_name' => 'Activity Name',
            'release_by' => 'Release By',
            'category' => 'Category',
            'status' => 'Status',
            'location' => 'Location',
            'release_at' => 'Release At',
            'ticketing_start_at' => 'Ticketing Start At',
            'ticketing_end_at' => 'Ticketing End At',
            'start_at' => 'Start At',
            'end_at' => 'End At',
            'updated_at' => 'Updated At',
            'introduction' => 'Introduction',
            'current_people' => 'Current People',
            'max_people' => 'Max People',
            'current_serial' => 'Current Serial',
            'pic_url' => 'Pic Url',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReleaseBy()
    {
        return $this->hasOne(TkOrganizer::className(), ['id' => 'release_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTkActivityEvents()
    {
        return $this->hasMany(TkActivityEvent::className(), ['activity_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTkTickets()
    {
        return $this->hasMany(TkTicket::className(), ['activity_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTkTicketEvents()
    {
        return $this->hasMany(TkTicketEvent::className(), ['activity_id' => 'id']);
    }
}
