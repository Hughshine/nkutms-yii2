<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "tk_notice".
 *
 * @property int $id ID
 * @property string $title 标题
 * @property string $content 内容
 * @property int $updated_at 上一次编辑时间
 * @property int $created_at 创建时间
 */
class Notice extends \yii\db\ActiveRecord
{

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
        return [
            [['updated_at', 'created_at'], 'integer'],
            [['title'], 'string', 'max' => 32],
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
