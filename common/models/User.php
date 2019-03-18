<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\IdentityInterface;
use yii\filters\RateLimitInterface;

/**
 * This is the model class for table "tk_user".
 *
 * @property int $id
 * @property string $user_name
 * @property string $wechat_id
 * @property int $category 标记用户类别0-学生1-教职员工2-其他
 * @property string $credential 该用户类别下，他的证件号。web端使用此为账号进行登录
 * @property string $password
 * @property string $access_token
 * @property string $created_at
 * @property int $expire_at
 * @property int $updated_at
 * @property int $allowance 用于限制访问频率
 * @property int $allowance_updated_at
 *
 * @property Ticket[] $tkTickets
 * @property TicketEvent[] $tkTicketEvents
 */

//定义用户的分类常量
define('USER_CATEGORY',
    [
        '0'=>'USER_0','1'=>'USER_1','2'=>'USER_2','3'=>'USER_3'
    ]);
class User extends \yii\db\ActiveRecord  implements IdentityInterface, RateLimitInterface
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
            [
                ['credential'],
                'required'
            ],
            [
                ['id','created_at','category', 'expire_at', 'updated_at', 'allowance', 'allowance_updated_at'],
                'integer'
            ],
            [
                ['credential','wechat_id'],
                'unique'
            ],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => '这个邮箱已经被注册'],

            [['user_name'], 'string', 'max' => 32],

            [['wechat_id', 'credential', 'password', 'access_token'], 'string', 'max' => 255],

            ['status', 'default', 'value' => self::STATUS_ACTIVE],

            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],

            [
                'category', 'compare',
                'compareValue'=>0,
                'operator' => '>=','message'=>'分类无效',
            ],
            [
                'category', 'compare',
                'compareValue'=>count(USER_CATEGORY),
                'operator' => '<','message'=>'分类无效',
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
            'user_name' => '名字',
            'wechat_id' => '微信 ID',
            'category' => '类别',
            'credential' => '账号',
            'password' => '密码',
            'access_token' => 'Access Token',
            'created_at' => '注册时间',
            'expire_at' => 'Expire At',
            'updated_at' => 'Update At',
            'status' => '状态',
            'allowance' => '用于限制访问频率',
            'allowance_updated_at' => 'Allowance Updated At',
        ];
    }

    public function fields(){
        return [
            'user_id' => 'id',
            'user_name',
            'category' => function($model)
            {
                return USER_CATEGORY[$model->category];
            },
            'credential',
        ];
    }

    public function getId(){ return $this->id; /*$this->getPrimaryKey()*/}

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
        ->where(['>','expire_at',time()])
        ->limit(1)
        ->one();
        //return static::findOne(['id' => 1]);
        //throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
        
        
        //  throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    // 
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }
    //用于admin端查找User
    public static function findIdentity_admin($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * Finds user by user_name
     *
     * @param string $user_name
     * @return static|null
     */
    public static function findByUsername($user_name)
    {
        return static::findOne(['user_name' => $user_name, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    // allowance => 控制api访问次数
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
        $this->allowance = $allowance;
        $this->allowance_updated_at = $timestamp;//time();
        $this->save();
    }

    public function editAndSaveUser($user,$name,$category,$credential)
    {
        $user->user_name = $name==null?$user->user_name:$name;
        $user->category = $category==null?$user->category:$category;
        $user->credential = $credential==null?$user->credential:$credential;

        $user->save(false);

        return $user;
    }
}
