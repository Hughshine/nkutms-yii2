<?php

namespace admin\models;


use common\models\Organizer;
use Yii;

class TkActivity extends \yii\db\ActiveRecord
{
    const STATUS_UNAUDITED  = 0;//未审核状态
    const STATUS_APPROVED = 1;//已批准状态
    const STATUS_REJECTED= 2;//被驳回状态
    public $time_start_stamp;
    public $time_end_stamp;
    public $ticket_start_stamp;
    public $ticket_end_stamp;
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
            //要求必须存在值
            [
                [
                    'activity_name',
                    'introduction' , 
                    'release_by',
                    'location',
                    'time_start_stamp',
                    'ticket_start_stamp',
                    'time_end_stamp',
                    'ticket_end_stamp',
                    'max_people',
                ], 
                'required'
            ],
            [
                //要求为整数
                [
                    'category', 
                    'status', 
                    'max_people',
                    'current_serial',
                ], 
                'integer'
            ],

            [['activity_name', 'introduction','location'], 'string','min'=>'2', 'max' => 255],
            ['activity_name', 'unique', 'targetClass' => '\admin\models\TkActivity', 'message' => '这个名字已经被注册'],

            ['status', 'in', 'range' => [self::STATUS_UNAUDITED, self::STATUS_APPROVED,self::STATUS_REJECTED]],

            ['category', 'in', 'range' => [0,1]],

            ['category','default','value'=>'0'],
            ['current_people','default','value'=>'0'],
            ['status', 'default', 'value' => self::STATUS_UNAUDITED],
            //['org_name','safe'],
            ['time_start_stamp', 'compare','compareValue'=>date('Y-m-d H:i' , time()+7*3600), 'operator' => '>','message'=>'不能早于当前的时间'],
            ['ticket_start_stamp', 'compare','compareValue'=>date('Y-m-d H:i' , time()+7*3600), 'operator' => '>','message'=>'不能早于当前的时间'],
            ['time_end_stamp', 'compare','compareAttribute'=>'time_start_stamp', 'operator' => '>','message'=>'结束时间不能早于开始时间'],
            ['ticket_end_stamp', 'compare','compareAttribute'=>'ticket_start_stamp', 'operator' => '>','message'=>'结束时间不能早于开始时间'],

            //外键要求
            [['release_by'], 'exist', 'skipOnError' => false, 'targetClass' => 'common\models\Organizer', 'targetAttribute' => ['release_by' => 'id']],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
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
            'release_by' => '发布者ID',
            'release_at' => '发布时间',
            'updated_at' => '上一次编辑时间',
            'ticketing_start_at' => '票务发布时间',
            'ticket_start_stamp' => '票务发布时间',
            'ticket_end_stamp' => '票务结束时间',
            'ticketing_end_at' => '票务结束时间',
            'current_serial'=>'票务序列号',
            'location'=>'活动地点',
        ];
    }
    /*原理未知，用于在活动index页面中将release_by字段替换成对应的组织者名字
     * 具体功能应该是在对应的Search类里得到组织者类的具体记录
     * */
    public function getOrganizer()
    {
        return $this->hasOne(Organizer::className(),['id'=>'release_by']);
    }
}
