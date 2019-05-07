<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "user_activity".//针对视图作业
 *
 * @property int $id
 * @property string $activity_name
 * @property int $category 标记活动类别 0-讲座 1-文艺 2-其他 3-未知
 * @property int $organizer_id organizer/-id
 * @property string $organizer_name 应必须填写
 * @property int $updated_at
 * @property string $location
 * @property string $introduction 介绍
 * @property int $ticketing_start_at
 * @property int $ticketing_end_at
 * @property int $start_at
 * @property int $end_at
 */
class ActivityBriefInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_activity';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'category', 'organizer_id', 'updated_at', 'start_at', 'end_at'], 'integer'],
            [['introduction'], 'string'],
            [['activity_name', 'organizer_name'], 'string', 'max' => 32],
            [['location'], 'string', 'max' => 255],
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
            'category' => 'Category',
            'organizer_id' => 'Organizer ID',
            'organizer_name' => 'Organizer Name',
            'updated_at' => 'Updated At',
            'location' => 'Location',
            'introduction' => 'Introduction',
            'ticketing_start_at' => 'Ticketing Start At',
            'ticketing_end_at' => 'Ticketing End At',
            'start_at' => 'Start At',
            'end_at' => 'End At',
        ];
    }

    public function fields()
    {
        return [
            "id",
            "activity_name",
            "organizer_name",
            "category" => function($model)
            {
                if(!is_numeric($model->category)||$model->category>=count(ACT_CATEGORY)||$model->category<0)
                    return '未知';
                return ACT_CATEGORY[$model->category];
            },
            "location",
            "start_at",
            "end_at",
            "current_people",
            "max_people",
        ];
    }
}
