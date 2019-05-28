<?php

namespace common\models;

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
 * @property int $current_serial 用于产生的序列号
 * @property int $ticketing_start_at 报名开始时间
 * @property int $ticketing_end_at 报名开始时间
 * @property string $pic_url 暂不支持传入图片
 * @property string $summary 摘要字段
 * @property Organizer $releaseBy
 * @property Ticket[] $tkTickets
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

            ['ticketing_start_at', 'compare','compareAttribute'=>'ticketing_end_at', 'operator' => '<','message'=>'报名开始时间不能晚于结束时间'],

            ['ticketing_end_at', 'compare','compareAttribute'=>'start_at', 'operator' => '<','message'=>'报名结束时间不能晚于活动开始时间'],
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
            'ticketing_start_at'=>'报名开始时间',
            'ticketing_end_at'=>'报名结束时间',
            'updated_at' => '字段更新时间',
            'created_at'=>'字段创建时间',
            'introduction' => '介绍',
            'current_people' => '当前人数',
            'max_people' => '最大人数',
            'current_serial' => '序列号',
            'pic_url' => '图片',
            'summary' => '摘要',
        ];
    }

    /**
     * 用于查找一个有效的活动
     * @param integer $id
     * @return Activity|null
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id,'status'=>self::STATUS_APPROVED]);
    }

    /**
     * 用于查找一个活动
     * @param integer $id
     * @return Activity|null
     */
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
                if(!is_numeric($model->category)||$model->category>=count(ACT_CATEGORY)||$model->category<0)
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
}
