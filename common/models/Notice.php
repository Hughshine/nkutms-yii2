<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "tk_notice".
 *
 * @property int $id ID
 * @property string $title 标题
 * @property string $content 内容
 * @property int $updated_at 上一次编辑时间
 * @property int $created_at 创建时间
 * @property int $status 创建时间
 * @property string $summary 创建时间
 */
class Notice extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    //TODO
    public function behaviors()
    {
        return [
            // Other behaviors
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tk_notice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return
        [
            [['updated_at', 'created_at','status'], 'integer'],
            [['title'], 'string', 'max' => 32],

            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return
            [
                'id' => 'ID',
                'title' => '标题',
                'content' => '内容',
                'updated_at' => '上一次编辑时间',
                'created_at' => '创建时间',
            ];
    }
}
