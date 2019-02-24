<?php

namespace orgapi\models;
//已弃用
use Yii;

use yii\web\IdentityInterface;

use yii\filters\RateLimitInterface;
/**
 * This is the model class for table "tk_organizer".
 *
 * @property int $id
 * @property string $org_name 应必须填写
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
 * @property ActivityEvent[] $tkActivityEvents
 */
class Organizer extends \yii\db\ActiveRecord implements IdentityInterface, RateLimitInterface
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
            [['org_,name'], 'string', 'max' => 32],
            // [['wechat_id1', 'credential', 'password', 'access_token', 'wechat_id2', 'wechat_id3'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'org_name' => '应必须填写',
            // 'wechat_id1' => '一个社团最多有三个管理者，暂时不考虑一个人管理多个社团',
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
            // 'wechat_id2' => 'Wechat Id2',
            // 'wechat_id3' => 'Wechat Id3',
        ];
    }

    public function fields()
    {
        return [
            "id",
            "org_name" => "org_name",
            "credential",
            // "access_token": null,
            // "signup_at",
            "logged_at",
            // "updated_at": 0,
            // "expire_at": 0,
            // "allowance": null,
            // "allowance_updated_at": 0,
            "category" => function($model)
            {
                switch ($model->category) {
                    case 0:
                        return '校级组织';
                        break;
                    case 1:
                        return '学生社团';
                        break;
                    case 2:
                        return '其他';
                        break;
                    default:
                        return '未知';
                        break;
                }
            },
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivityEvents()
    {
        return $this->hasMany(ActivityEvent::className(), ['organizer_id' => 'id']);
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
    public static function findIdentity($id){}

    public function getId(){ return $this->id; }

    public function validateAuthKey($authKey){}

    public function getAuthKey(){}

    public function getRateLimit($request, $action)
    {
        return [2,3];
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
