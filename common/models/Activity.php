<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%tk_activity}}".
 *
 * @property int $id
 * @property string $name
 * @property int $release_by organizer/-id
 * @property int $category 标记用户类别0-学生1-教职员工2-其他
 * @property int $status 
 * @property string $location
 * @property string $release_at
 * @property int $start_at
 * @property string $end_at
 * @property int $update_at
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
        return '{{%tk_activity}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['release_by', 'category', 'status', 'start_at', 'update_at', 'current_people', 'max_people', 'current_serial'], 'integer'],
            [['release_at'], 'safe'],
            [['name'], 'string', 'max' => 32],
            [['location'], 'string', 'max' => 64],
            [['end_at', 'introduction', 'pic_url'], 'string', 'max' => 255],
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
            'name' => 'Name',
            'release_by' => 'organizer/-id',
            'category' => '标记用户类别0-学生1-教职员工2-其他',
            'status' => '该用户类别下，他的证件号',
            'location' => 'Location',
            'release_at' => 'Release At',
            'start_at' => 'Start At',
            'end_at' => 'End At',
            'update_at' => 'Update At',
            'introduction' => '介绍',
            'current_people' => 'Current People',
            'max_people' => 'Max People',
            'current_serial' => '用于产生票务的序列号',
            'pic_url' => '暂不支持传入图片',
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

    public function fields()
    {
        return [
            "id",
            "name",
            "release_by",
            "category",
            "status",
            "location",
            "release_at",
            "start_at",
            "end_at",
            "introduction",
            "current_people",
            "max_people",
        ];
    }

    public function extraFields()
    {

    }
}
