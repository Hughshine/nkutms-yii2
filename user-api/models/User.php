<?php

namespace userapi\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\IdentityInterface;
use yii\filters\RateLimitInterface;
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
 * @property Ticket[] $tkTickets
 * @property TicketEvent[] $tkTicketEvents
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface, RateLimitInterface
{
    //TODO
    public function behaviors()
    {
        return [
            // Other behaviors
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'signup_at',
                'updatedAtAttribute' => 'update_at',
                // 'value' => new Expression('NOW()'),
            ],
        ];   
    } 
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
            [['wechat_id'], 'required'],
            [['category', 'signup_at', 'logged_at', 'expire_at', 'update_at', 'allowance', 'allowance_updated_at'], 'integer'],
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
    public function getTickets()
    {
        return $this->hasMany(Ticket::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTicketEvents()
    {
        return $this->hasMany(TicketEvent::className(), ['user_id' => 'id']);
    }

    public function generateAccessToken()
    {
        return $this->access_token = Yii::$app->security->generateRandomString();
    }
    

    public static function findIdentityByAccessToken($token, $type = null)
    {
        
        //findIdentityByAccessToken()方法的实现是系统定义的
        //例如，一个简单的场景，当每个用户只有一个access token, 可存储access token 到user表的access_token列中， 方法可在User类中简单实现，如下所示：
        return static::find(['access_token' => $token])
        ->where(['>','expire_at',time()])//授权时间为一天
        ->limit(1)
        ->one();
        //return static::findOne(['id' => 1]);
        //throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
        
        
        //  throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    // 
    public static function findIdentity($id){}

    public function getId(){ return $this->id; }

    public function validateAuthKey($authKey){}

    public function getAuthKey(){}

    public function getRateLimit($request, $action)
    {
        return [2,1];
    }

    public function loadAllowance($request, $action)
    {
        return [$this->allowance, $this->allowance_updated_at];
    }

    public function saveAllowance($request,$action,$allowance,$timestamp)
    {
        // echo $allowance;
        // echo $timestamp;
        $this->allowance = $allowance;
        $this->allowance_updated_at = $timestamp;//time();
        $this->save();
    }
}
