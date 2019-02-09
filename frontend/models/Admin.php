<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tk_admin".
 *
 * @property int $id
 * @property string $name
 * @property string $password
 * @property string $signup_at
 * @property string $logged_at 上次登入时间
 *
 * @property TkActivityEvent[] $tkActivityEvents
 * @property TkTicketEvent[] $tkTicketEvents
 */
class Admin extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tk_admin';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['signup_at', 'logged_at'], 'safe'],
            [['logged_at'], 'required'],
            [['name'], 'string', 'max' => 32],
            [['password'], 'string', 'max' => 255],
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
            'password' => 'Password',
            'signup_at' => 'Signup At',
            'logged_at' => '上次登入时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTkActivityEvents()
    {
        return $this->hasMany(TkActivityEvent::className(), ['operated_by_admin' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTkTicketEvents()
    {
        return $this->hasMany(TkTicketEvent::className(), ['operated_by_admin' => 'id']);
    }
}
