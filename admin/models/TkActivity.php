<?php

namespace admin\models;


use admin\models\organizer;
use Yii;

class TkActivity extends \yii\db\ActiveRecord
{
    const STATUS_UNAUDITED  = 0;//未审核状态
    const STATUS_APPROVED = 1;//已批准状态
    const STATUS_REJECTED= 2;//被驳回状态
    public $time_start_stamp;
    public $time_end_stamp;
    public $org_name;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tk_activity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return 
        [
            [
                [
                    'activity_name', 
                    'introduction' , 
                    'release_by', 
                    'release_at', 
                    'updated_at',
                    'time_start_stamp',
                    'time_end_stamp',
                    'max_people',
                ], 
                'required'
            ],
            [
                [
                    'category', 
                    'status', 
                    'max_people', 
                    'current_people', 
                    'start_at', 
                    'end_at',  
                    'release_at', 
                    'updated_at'
                ], 
                'integer'
            ],
            [['activity_name', 'introduction'], 'string', 'max' => 255],
            ['status', 'default', 'value' => self::STATUS_UNAUDITED],
            ['status', 'in', 'range' => [self::STATUS_UNAUDITED, self::STATUS_APPROVED,self::STATUS_REJECTED]],

            ['category','default','value'=>'0'],
            ['category', 'in', 'range' => [0,1]],

            ['org_name','safe'],

            ['time_end_stamp', 'compare','compareAttribute'=>'time_start_stamp', 'operator' => '>','message'=>'结束时间不能早于开始时间'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'activity_name' => '活动名字',
            'category' => '活动类别',
            'status' => '状态',
            'introduction' => '活动简介',
            'max_people' => '人数限制',
            'current_people' => '当前人数',
            'start_at' => '活动开始时间',
            'time_start_stamp' => '活动开始时间',
            'end_at' => '活动结束时间',
            'time_end_stamp' => '活动结束时间',
            'release_by' => '组织者ID',
            'release_at' => '发布时间',
            'updated_at' => '上一次编辑时间',
        ];
    }

    public function getOrganizer()
    {
        return $this->hasOne(Organizer::className(),['id'=>'release_by']);
    }
}
