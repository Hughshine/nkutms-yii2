<?php
namespace common\models;

use Yii;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

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
 * @property string $created_at
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

//定义组织者的分类常量
define('ORG_CATEGORY',
    [
        '0'=>'ORG_0','1'=>'ORG_1','2'=>'ORG_2','3'=>'ORG_3'
    ]);

class Organizer extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const CATEGORY_SCHOOL_ORG = 0;
    const CATEGORY_STUDENT_ORG = 1;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tk_organizer';
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
    public function rules()
    {
        return [
            [
                [
                    'org_name',
                    'credential',
                    'password',
                    'category',
                    'status',
                ],
                'required',
            ],
            [
                [
                    'allowance',
                    'allowance_updated_at',
                    'category',
                    'created_at',
                    'expire_at',
                    'logged_at',
                    'status',
                    'updated_at',
                ],
                'integer',
            ],

            [['credential',], 'unique'],

            [
                'status', 'in', 'range' =>
                    [
                        self::STATUS_ACTIVE, self::STATUS_DELETED
                    ],
            ],
            [
                'category', 'in', 'range' =>
                    [
                        self::CATEGORY_SCHOOL_ORG,self::CATEGORY_STUDENT_ORG
                    ],
            ],

            [['org_name'], 'string', 'max' => 32],

            [['credential', 'password', 'access_token'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'org_name' => '名称',
            'category' => '标记用户类别 0-校级组织，1-学生社团',
            'credential' => '账号',
            'password' => '密码',
            'access_token' => 'Access Token',
            'created_at' => '注册时间',
            'logged_at' => '上一次登录时间',
            'updated_at' => '上一次更新时间',
            'expire_at' => 'Expire At',
            'allowance' => '用于限制访问频率',
            'allowance_updated_at' => 'Allowance Updated At',
        ];
    }

    public function fields()
    {
        return [
            "id",
            "org_name" => "org_name",
            "credential",
            // "access_token": null,
            // "created_at",
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



    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    //上一个方法的copy，但是不检查状态是否有效，用于管理员查找对应组织者时更全面
    public static function findIdentity_admin($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by org_name
     *
     * @param string $org_name
     * @return static|null
     */
    public static function findByUsername($org_name)
    {
        return static::findOne(['org_name' => $org_name, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByCredential($cre)
    {
        return static::findOne(['credential' => $cre, 'status' => self::STATUS_ACTIVE]);
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
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
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


    public function editAndSaveOrganizer($organizer,$org_id,$org_name,$category,$credential,$newpassword)
    {
        $organizer->org_id = $org_id==null?$organizer->org_id:$org_id;
        $organizer->org_name = $category==null?$organizer->org_name:$org_name;
        $organizer->category = $category==null?$organizer->category:$category;
        $organizer->credential = $credential==null?$organizer->credential:$credential;
        $organizer->password = Yii::$app->getSecurity()->generatePasswordHash($newpassword);

        $organizer->save(false);
    }
}
