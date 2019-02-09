<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tk_user".
 *
 * @property int $id
 * @property string $name
 * @property string $wechat_id
 * @property int $category 标记用户类别0-学生1-教职员工2-其他
 * @property string $credential 该用户类别下，他的证件号。web端使用此为账号进行登录
 * @property string $password
 * @property string $access_token
 * @property string $signup_at
 * @property int $logged_at 使用int类型便于比较操作
 * @property int $expire_at
 * @property int $update_at
 * @property int $allowance 用于限制访问频率
 * @property int $allowance_updated_at
 *
 * @property TkTicket[] $tkTickets
 * @property TkTicketEvent[] $tkTicketEvents
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tk_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['wechat_id', 'credential'], 'required'],
            [['category', 'logged_at', 'expire_at', 'update_at', 'allowance', 'allowance_updated_at'], 'integer'],
            [['signup_at'], 'safe'],
            [['name'], 'string', 'max' => 32],
            [['wechat_id', 'credential', 'password', 'access_token'], 'string', 'max' => 255],
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
            'wechat_id' => 'Wechat ID',
            'category' => '标记用户类别0-学生1-教职员工2-其他',
            'credential' => '该用户类别下，他的证件号。web端使用此为账号进行登录',
            'password' => 'Password',
            'access_token' => 'Access Token',
            'signup_at' => 'Signup At',
            'logged_at' => '使用int类型便于比较操作',
            'expire_at' => 'Expire At',
            'update_at' => 'Update At',
            'allowance' => '用于限制访问频率',
            'allowance_updated_at' => 'Allowance Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTkTickets()
    {
        return $this->hasMany(TkTicket::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTkTicketEvents()
    {
        return $this->hasMany(TkTicketEvent::className(), ['user_id' => 'id']);
    }
}
