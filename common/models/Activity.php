<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%tk_activity}}".
 *
 * @property int $id
 * @property string $activity_name
 * @property int $release_by organizer/-id
 * @property int $category 标记用户类别0-学生1-教职员工2-其他
 * @property int $status 
 * @property string $location
 * @property string $release_at
 * @property int $start_at
 * @property string $end_at
 * @property int $updated_at
 * @property string $introduction 介绍
 * @property int $current_people
 * @property int $max_people
 * @property int $current_serial 用于产生票务的序列号
 * @property string $pic_url 暂不支持传入图片
 *
 * @property Organizer $releaseBy
 * @property ActivityEvent[] $tkActivityEvents
 * @property Ticket[] $tkTickets
 * @property TicketEvent[] $tkTicketEvents
 */


class Activity extends ActiveRecord
{
    const STATUS_UNAUDITED  = 0;//未审核状态
    const STATUS_APPROVED = 1;//已批准状态
    const STATUS_REJECTED= 2;//被驳回状态
    const STATUS_CANCEL= 3;//被取消状态


    public $org_name;//用于admin端查找发布者名字


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
            [
                [
                    'activity_name',
                    'category',
                    'current_people',
                    'current_serial',
                    'introduction' ,
                    'location',
                    'max_people',
                    'release_by',
                    'start_at', 'end_at',
                    'status',
                    'ticketing_start_at', 'ticketing_end_at',
                ],
                'required',
            ],
            [
                [
                    'release_by',
                    'category',
                    'status',
                    'start_at', 'end_at',
                    'current_people',
                    'max_people',
                    'current_serial',
                    'ticketing_start_at', 'ticketing_end_at',
                    'release_at'
                ]
                , 'integer'
            ],
            [['activity_name'], 'string', 'max' => 32],

            [['location'], 'string', 'max' => 64],

            [['introduction',], 'string', 'max' => 255],

            ['status', 'in', 'range' =>
                [
                    self::STATUS_UNAUDITED,
                    self::STATUS_APPROVED,
                    self::STATUS_REJECTED,
                    self::STATUS_CANCEL,
                ]],

            [
                'category', 'compare',
                'compareValue'=>0,
                'operator' => '>=','message'=>'活动分类无效',
            ],
            [
                'category', 'compare',
                'compareValue'=>count(ACT_CATEGORY),
                'operator' => '<','message'=>'活动分类无效',
            ],

            [['release_by'], 'exist', 'skipOnError' => true, 'targetClass' => Organizer::className(), 'targetAttribute' => ['release_by' => 'id']],

            ['current_people', 'compare','compareAttribute'=>'max_people', 'operator' => '<=','message'=>'活动参与人数不能大于活动最大人数'],

            ['start_at', 'compare','compareAttribute'=>'end_at', 'operator' => '<','message'=>'活动开始时间不能晚于结束时间'],

            ['ticketing_start_at', 'compare','compareAttribute'=>'ticketing_end_at', 'operator' => '<','message'=>'票务开始时间不能晚于结束时间'],

            ['ticketing_end_at', 'compare','compareAttribute'=>'start_at', 'operator' => '<','message'=>'票务结束时间不能晚于活动开始时间'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),//自动填充时间字段功能
                'attributes' => [
                    //当插入时填充created_at和updated_at
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    //当更新时填充updated_at
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'activity_name' => '活动名称',
            'release_by' => '发布者ID',
            'category' => '活动类别',
            'status' => '状态',
            'location' => '活动地点',
            'release_at' => '发布时间',
            'start_at' => '活动开始时间',
            'end_at' => '活动结束时间',
            'ticketing_start_at'=>'票务开始时间',
            'ticketing_end_at'=>'票务结束时间',
            'updated_at' => '字段更新时间',
            'created_at'=>'字段创建时间',
            'introduction' => '介绍',
            'current_people' => '当前人数',
            'max_people' => '最大人数',
            'current_serial' => '票务的序列号',
            'pic_url' => '图片',
        ];
    }

    //用于admin端查找活动的名称
    public static function findIdentity_admin($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReleaseBy()
    {
        return $this->hasOne(Organizer::className(), ['id' => 'release_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTickets()
    {
        return $this->hasMany(Ticket::className(), ['activity_id' => 'id']);
    }


    public function fields()
    {
        return [
            "id",
            "activity_name",
            "organizer_id" => "release_by",
            "organizer_name" => function($model)
            {
                if($model->releaseBy == null)
                    return '无发布者';
                return $model->releaseBy->org_name;
            },
            "category" => function($model)
            {
                if($model->category>=count(ACT_CATEGORY)||$model->category<0)
                    return '未知';
                return ACT_CATEGORY[$model->category];
            },
            "status" => function($model)
            {
                switch ($model->status) 
                {
                    case 0:
                        return '待审核';
                        break;
                    case 1:
                        return '通过';
                        break;
                    case 2:
                        return '被驳回';
                        break;
                    default:
                        return '取消';
                        break;
                }
            },
            "location",
            "release_at",
            "start_at",
            "end_at",
            "ticketing_start_at",
            "ticketing_end_at",
            "introduction",
            "current_people",
            "max_people",
        ];
    }

    public function generateAndWriteNewActivity($org_id,$activity_name,$category,$location,$ticketing_start_at,$ticketing_end_at,$start_at,$end_at,$max_people,$intro)
    {
        $activity = new Activity();
        //太可怕了
        $activity->release_by = $org_id;
        $activity->activity_name = $activity_name;
        $activity->category = $category;
        $activity->location = $location;
        $activity->status = 0;
        $activity->ticketing_start_at = $ticketing_start_at;
        $activity->ticketing_end_at = $ticketing_end_at;
        $activity->start_at = $start_at;
        $activity->end_at = $end_at;
        $activity->release_at = time()+7*3600;
        $activity->max_people = $max_people;
        $activity->introduction = $intro;
        $activity->current_people = 0;
        $activity->current_serial = 1;
        $activity->save(false);

        return $activity;
    }

    public function editAndSaveActivity($activity,$activity_name,$category,$location,$ticketing_start_at,$ticketing_end_at,$start_at,$end_at,$max_people,$intro)
    {
        $activity->activity_name = $activity_name==null?$activity->activity_name:$activity_name;

        $activity->category = $category==null?$activity->category:$category;
        $activity->location = $location==null?$activity->location:$location;
        $activity->ticketing_start_at = $ticketing_start_at==null?$activity->ticketing_start_at:$ticketing_start_at;
        $activity->ticketing_end_at = $ticketing_end_at==null?$activity->ticketing_end_at:$ticketing_end_at;
        $activity->start_at = $start_at==null?$activity->start_at:$start_at;
        $activity->end_at = $end_at==null?$activity->end_at:$end_at;
        $activity->max_people = $max_people==null?$activity->max_people:$max_people;
        $activity->introduction = $intro==null?$activity->introduction:$intro;
        $activity->save(false);
    }
}
