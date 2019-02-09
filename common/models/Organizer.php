<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tk_organizer".
 *
 * @property int $id
 * @property string $name 应必须填写
 * @property string $wechat_id1 一个社团最多有三个管理者，暂时不考虑一个人管理多个社团
 * @property int $category 标记用户类别 0-校级组织，1-学生社团
 * @property string $credential 该用户类别下，他的证件号
 * @property string $password
 * @property string $access_token
 * @property string $signup_at
 * @property int $logged_at 使用int类型便于比较操作
 * @property int $updated_at
 * @property int $expire_at
 * @property int $allowance 用于限制访问频率
 * @property int $allowance_updated_at
 * @property string $wechat_id2
 * @property string $wechat_id3
 *
 * @property TkActivityEvent[] $tkActivityEvents
 */
class Organizer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tk_organizer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category', 'logged_at', 'updated_at', 'expire_at', 'allowance', 'allowance_updated_at'], 'integer'],
            [['credential'], 'required'],
            [['signup_at'], 'safe'],
            [['name'], 'string', 'max' => 32],
            [['wechat_id1', 'credential', 'password', 'access_token', 'wechat_id2', 'wechat_id3'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '应必须填写',
            'wechat_id1' => '一个社团最多有三个管理者，暂时不考虑一个人管理多个社团',
            'category' => '标记用户类别 0-校级组织，1-学生社团',
            'credential' => '该用户类别下，他的证件号',
            'password' => 'Password',
            'access_token' => 'Access Token',
            'signup_at' => 'Signup At',
            'logged_at' => '使用int类型便于比较操作',
            'updated_at' => 'Updated At',
            'expire_at' => 'Expire At',
            'allowance' => '用于限制访问频率',
            'allowance_updated_at' => 'Allowance Updated At',
            'wechat_id2' => 'Wechat Id2',
            'wechat_id3' => 'Wechat Id3',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTkActivityEvents()
    {
        return $this->hasMany(TkActivityEvent::className(), ['organizer_id' => 'id']);
    }
}
